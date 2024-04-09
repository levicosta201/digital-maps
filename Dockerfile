FROM ubuntu:latest
LABEL authors="levi"

ENTRYPOINT ["top", "-b"]
