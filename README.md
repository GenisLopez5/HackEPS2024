# HackEPS2024 - Parking Challenge

Our team has participated in the Hackathon hosted by Universitat de LLeida (EPS), where we implemented a solution for the project presented by the city council.

## The Challenge

We were asked to develop a system to monitor and manage the availability of spaces in an outdoor parking lot. The main idea was to use the sensors and the Raspberry Pi to detect the occupancy of parking spaces, allowing real-time monitoring of the number of available spots. Based on this information, a series of extra functionalities must also be developed.

## Authors
- Jaume López
- Pau Carulla
- Genís López
- Pau López

---
<br>

>[!NOTE]  
> ### Project Technologies and Tools
> Here is the summary of the technologies and tools utilized in our project:
> - **Laravel (php)**: Main framework for the web application development.
> - **Tailwind (CSS)**: Used for efficient and flexible styling in the web.
> - **Python**: Leveraged for the backend development.
> - **Pandas** and **Sklearn**: Employed for data manipulation and analysis, and working with .csv files.
> - **OpenStreetMap**: Integrated for rich mapping functionalities.
> - **Leaflet** and **Mapbox**: Employed for the interactive map in our web.

<br><br>
# Table of Contents

1. [Project Overview](#project-ov)
2. [Challenges we ran into](#callenge)
3. [Project Setup](#project-setup)
4. [Learning](#learning)
5. [Next](#next)
6. [Screenshots](#screen)
7. [Licence](#license)<br><br>

<a name="project-ov"></a>
## Project Overview
We used Laravel, a full-stack framework, to develop an API that processes input from a Raspberry Pi. The API generates a dataset from the received data and trains a machine learning model to predict future trends.

To present this data, we built a web application featuring a map that displays available parking spots in Lleida, Spain. The map can also be transformed into a heatmap to show how crowded specific parking areas are. Users can search for the nearest available parking spot.

Upon selecting a parking location, users are provided with detailed information, including its exact location, current occupancy levels in real time, historical occupancy data, and predictions based on past trends.

<a name="callenge"></a>
## Challenges we ran into
First of all, we had to set up the Rasberry Pi having no prior experience. Next we designed the software arquitecture and funcitonalities that our solution must offer. We also needed to develop a mobile application, having no prior experience with android studio. And last but not least, we had to think of how to collect the data from the sensors, store it correctly into usable files and develop a mothod to predict the occupation for the next period of time. We also had to put all think together, integrating python scripts into php code.

<a name="project-setup"></a>
## Project Setup
First of all, we need to run the solver in order to compile its results into a readable json file:

To do that, we'll need to create a virtualenviroment and install all the requirements
```
cd ./solver
```
```
virtualenv env --python=python3.10
```
```
source env/bin/activate
```
```
pip install -r requirements.txt
```
Once created, we'll run the solver once and then, enable the FlaskAPI service to allow access to the json computed file (that contains all events in the correct order) to the backend (Laravel).
```
python3 solver.py
```
Now, run the FlaskAPI service:
```
cd ./web_server
```
```
python3 app.py
```

Once having the FlaskAPI service up, we'll need to enable the backend service (Laravel) with PHP 8.3.6
```
cd ../../web
```
```
composer install
```
Once installed all dependencies, now we can create a ``.env`` file by copying the `.env.example` and configuring all the standard credentials (we'll only need to set up the [database section if needed](https://www.inmotionhosting.com/support/edu/laravel/how-to-configure-the-laravel-env-for-a-database/), by default uses Sqlite3) 

Don't forget to add the DATA_URI credential with the value of the host of the FlaskAPI e.g. ``DATA_URI="http://127.0.0.1:5000/"`` if running in the same machine on port 5000.

If using in different devices, use [ngrok](https://ngrok.com/). (We used it for the demo)

```
php artisan migrate
```
```
php artisan serve
```
In another terminal, we'll launch the frontend active CSS compiling service using tailwindcss, (for development purposes)
```
cd .. && npm install
```
To recompile every time any file gets changed: 
```
npm run dev
```
To create a production css-compiled snapshot:
```
npm run build
```
Now, you're ready to go and all services are up!

You can enjoy the responsive web-app at 127.0.0.1:8000 (probably), or see the ``php artisan serve`` section and check which port is running on.


<a name="learning"></a>
## Learning 🎓
Through this project, we significantly enhanced our skills in Python, GitHub, Laravel, and Tailwind CSS. We also gained hands-on experience with Android Studio, learning how to develop mobile applications from scratch. Additionally, we explored how to connect to and program a Raspberry Pi, which gave us valuable insights into IoT development. We became proficient with Swagger for API documentation and learned how to work with pip and virtual environments to manage Python dependencies effectively.


<a name="next"></a>
## What's next for AparcApp
We're excited to enhance the user experience by leveraging the Log In/Register system. For example, we plan to send real-time notifications to users, showing how many parking slots are still available at their favorite parking locations. Additionally, we aim to implement OCR (Optical Character Recognition) technology at each parking entry to track which cars are parked in which spots.

<a name="license"></a>
## License ⚖️
Creative Commons Attribution Non Commercial No Derivatives 4.0 International <br><br><br>
