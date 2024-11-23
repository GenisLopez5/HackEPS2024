import pytesseract
from PIL import Image
import base64
import io

def read_license_plate(base64_string):

    # Decode base64 string to image
    image_data = base64.b64decode(base64_string)
    image = Image.open(io.BytesIO(image_data))

    # Convert to a format OpenCV can use
    if image.mode == 'RGBA':
        image = image.convert('RGB')

    #extract text from image
    text = pytesseract.image_to_string(image)

    return text.strip()

