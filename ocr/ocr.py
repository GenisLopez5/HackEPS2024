import pytesseract
from PIL import Image
import base64
import io

def base64_to_image(base64_string):

    # Decode base64 string to image
    image_data = base64.b64decode(base64_string)
    image = Image.open(io.BytesIO(image_data))

    # Convert to a format OpenCV can use
    if image.mode == 'RGBA':
        image = image.convert('RGB')

    return image
    """
    #extract text from image
    text = pytesseract.image_to_string(image)

    text.strip()
    """

def detect_and_read_plate(image):
    # Convert image to grayscale for better OCR performance
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Apply edge detection
    edges = cv2.Canny(gray, 100, 200)

    # Find contours
    contours, _ = cv2.findContours(edges, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)

    for contour in contours:
        # Approximate the contour
        approx = cv2.approxPolyDP(contour, 0.02 * cv2.arcLength(contour, True), True)

        if len(approx) == 4:  # Possible license plate (quadrilateral)
            x, y, w, h = cv2.boundingRect(approx)

            # Extract the plate region
            plate = gray[y:y+h, x:x+w]

            # OCR the extracted plate
            text = pytesseract.image_to_string(plate, config='--psm 8')
    return text
