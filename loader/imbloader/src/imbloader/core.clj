(ns imbloader.core
  (require [clojure.xml :as xml]
           [clojure.java.jdbc :as sql]
           [clojure.string :as s]))

(def testloc "../../testdata/road_closures.kml")

(defn getfile [uri]
  "Load the kml file from the supplied uri"
  (xml/parse uri))

(defn getnodes [xml node]
  (for [x (xml-seq xml) :when (= node (:tag x))] x))

(defn gettext [xml node]
  (let [n (getnodes xml node)]
    (if (empty? n)
      nil
      (first (:content (first n))))))

(defn placemarks [file]
  (getnodes file :Placemark))

(defn tomap [p]
  "Convert a placemark xml node to a map we can use"
  (let [nam (gettext p :name)
        desc (gettext p :description)
        coords (s/split (gettext p :coordinates) #"\,")
        lat (first coords)
        lon (second coords)
        ]
    {:name nam :description desc :latitude lat :longitude lon}))


(defn write [events]
  (sql/with-connection {:classname "com.mysql.jdbc.Driver"
          :subprotocol "mysql"
          :subname "//localhost:8886/inmb"
          :user "root"
          :password "root"} 
                        (sql/insert-records :events events)))

(defn loadfrom [uri]
  (let [file (getfile uri)
        placemarks (placemarks file)
        events (map tomap placemarks)]
    (write events)))
