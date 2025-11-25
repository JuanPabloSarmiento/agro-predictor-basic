FROM python:3.10-slim
WORKDIR /usr/src/app

COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY . /usr/src/app
EXPOSE 5000
CMD ["python", "api.py"]
