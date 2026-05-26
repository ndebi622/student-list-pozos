
FROM python:3.13-slim
LABEL maintainer="ndoumbendebi@gmail.com" \
      description="Student-list-API-POZOS"
COPY ./simple_api/student_age.py /student_age.py
COPY requirements.txt /
RUN apt update -y && \
    apt install -y \
    gcc \
    build-essential \
    python3-dev \
    libsasl2-dev \
    libldap2-dev \
    libssl-dev
RUN pip3 install -r /requirements.txt
RUN mkdir /data
VOLUME /data
EXPOSE 5000
CMD ["python3", "./student_age.py"]

