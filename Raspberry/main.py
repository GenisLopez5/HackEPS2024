import requests
import RPi.GPIO as GPIO
from time import sleep
import json

DETECTION_PIN = 17
JUMPER_PIN = 27
PARKING_ID = 1
CAR_ENTER_URL = "http://192.168.219.241:8000/api/cotxes/enter"
CAR_EXIT_URL = "http://192.168.219.241:8000/api/cotxes/exit"

# Raspberry pins configuration
def Setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(DETECTION_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    GPIO.setup(JUMPER_PIN, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
    return

car_in_sensor = False
sensor_enter_mode = True

#this code will be executed every frame
def Update():
    global car_in_sensor

    detecting_car = GPIO.input(DETECTION_PIN) == GPIO.LOW

    sensor_enter_mode = GPIO.input(JUMPER_PIN) == GPIO.HIGH

    if (detecting_car and not car_in_sensor):
        #wait and see
        sleep(0.1)

        if (GPIO.input(DETECTION_PIN) == GPIO.LOW):
            car_in_sensor = True
            print("Car detected")

            # send request to camera
            try:
                ret = None
                if (sensor_enter_mode):
                    ret = requests.post(CAR_ENTER_URL, 
                                        data={"parking_id": PARKING_ID}, json=None)
                else:
                    ret = requests.post(CAR_EXIT_URL, 
                                        data={"parking_id": PARKING_ID}, json=None)

                ret = json.loads(ret.text)
                
                print(ret['message'])
            except Exception as e:
                print(f"An error occurred: {e}")


    if (not detecting_car):
        #wait and see
        sleep(0.1)
        if (not GPIO.input(DETECTION_PIN) == GPIO.LOW):
            car_in_sensor = False

    return
    

if __name__ == "__main__":

    Setup()

    try:
        while (True):
            Update()
            sleep(0.05)

    except KeyboardInterrupt:
        print("Exiting program")
    
    finally:
        GPIO.cleanup()
        
