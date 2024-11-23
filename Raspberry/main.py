import requests
import RPi.GPIO as GPIO
from time import sleep

DETECTION_PIN = 17
CAR_DETECTED_URL = "http://192.168.219.241:8000/api/cotxe/new"

# Raspberry pins configuration
def Setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(DETECTION_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    return

car_in_sensor = False

#this code will be executed every frame
def Update():
    global car_in_sensor

    detecting_car = GPIO.input(DETECTION_PIN) == GPIO.LOW

    if (detecting_car and not car_in_sensor):
        #wait and see
        sleep(0.1)

        if (GPIO.input(DETECTION_PIN) == GPIO.LOW):
            car_in_sensor = True
            print("Car detected")

            # send request to camera
            try:
                ret = requests.post(CAR_DETECTED_URL, data=None, json=None)
                print(ret.text)
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
        
