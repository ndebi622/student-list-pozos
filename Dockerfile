FROM python:3.13-slim AS builder

LABEL maintainer="ndoumbendebi@gmail.com" \
      description="Student-list-API-POZOS"

WORKDIR /student-list-pozos/app

COPY requirements.txt .

RUN apt update && apt install -y \
    gcc \
    build-essential \
    python3-dev \
    libsasl2-dev \
    libldap2-dev \
    libssl-dev

#EMPECHE PIP de conserver du cache inutile
RUN pip install --no-cache-dir -r requirements.txt

COPY ./simple_api/student_age.py .

RUN mkdir /data
COPY ./simple_api/student_age.json /data/student_age.json

# =========================
# Stage final
# =========================

FROM python:3.13-slim

RUN apt update && apt install -y \
    libldap2-dev \
    libsasl2-dev \
    libssl-dev

WORKDIR /student-list-pozos/app

COPY --from=builder /usr/local/lib/python3.13 /usr/local/lib/python3.13
COPY --from=builder /usr/local/bin /usr/local/bin
COPY --from=builder /student-list-pozos/app .

VOLUME /data

EXPOSE 5000

CMD ["python3", "student_age.py"]
