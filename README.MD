# (A)rduino (S)im (D)ata (A)cquisition (S)ystem

![alt asdas](https://raw.githubusercontent.com/laresistenciadelbit/asdas/main/readme_images/smarphone-asdas.png)

[FULL PDF DOCUMENTATION (SPANISH)](https://github.com/laresistenciadelbit/varios/raw/master/ASDAS_PUBLIC.PDF)

[FULL PDF DOCUMENTATION LITE (SPANISH)(NO CODE DIAGRAMS)](https://github.com/laresistenciadelbit/varios/raw/master/ASDAS_PUBLIC_LITE.PDF)

## SPANISH

Arduino Sim Data Acquisition System es un proyecto que cubre toda la parte de
adquisición de datos de sensores desde el circuito físico, a la transmisión de los datos,su almacenaje, y su visualización en tiempo real desde un navegador Web.

Un dispositivo estación hecho con Arduino envía peticiones JSON HTTP POST al servidor.

Este repositorio de Github almacena la parte del servidor.

La parte de la estación la puedes ver en:
[https://github.com/laresistenciadelbit/asdas_station](https://github.com/laresistenciadelbit/asdas_station)

#### SERVIDOR WEB ASDAS

El servidor funciona en PHP, está basado en el framework Adminlte (Con bootstrap por detrás), y usa Leaflet.js para generar mapas y la librería Moment.js para procesar los datos en el cliente.

Puede ser usado con una base de datos SQLITE o MYSQL.

#### INTERFAZ
![alt asdas_interface](https://raw.githubusercontent.com/laresistenciadelbit/asdas/main/readme_images/asdas-interface.png)

(1) Barra lateral de acceso al área de configuración y a las distintas estaciones

(2) Barra superior de acceso a la página principal y a la de contacto

(3) Cuatro cajas que muestran estadísticas

(4) Gráfica que muestra los sensores de la o las estaciones

(5) Calendario en el que seleccionamos el día o mes para mostrar los datos

(6) Gráfica que muestra los estados de las estaciones

(7) Mapa que muestra la ubicación de cada estación
___
.
## ENGLISH

ASDAS is a project which covers all the data acquisition part of sensors starting from the physical circuit followed by the data transmission, its storage and visualization in real time from a Web Browser.

A station device made with Arduino send HTTP POST JSON messages to the server.

This Github project is the web server part.

The station Github can be accessed from:

[https://github.com/laresistenciadelbit/asdas_station](https://github.com/laresistenciadelbit/asdas_station)


#### ASDAS WEB SERVER

The server works in PHP, is based on Adminlte framework (Bootstrap behind), and uses Leaflet.js for generate maps and Moment.js for processing the data in the client side.

It can use SQLITE or MYSQL as database.

#### INTERFACE
![alt asdas_interface](https://raw.githubusercontent.com/laresistenciadelbit/asdas/main/readme_images/asdas-interface.png)

(1) Left sidebar for accessing to configuration or the different stations

(2) Top sidebar for accessing the main and contact page

(3) Four statistics boxes

(4) Graph with sensor data of the stations

(5) Calendar for selecting data from that day or month

(6) Graph with station states

(7) Map which shows the location of stations