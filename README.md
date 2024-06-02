# Weather Stations API

## Description

This project is a web service that provides information about weather stations in Latvia. The service is built using Symfony and exposes a REST API with two endpoints:

A list of all weather stations with Station_id and Name.
Detailed information for a specific station by Station_id.
The API uses HTTP bearer preshared key authentication to limit access and includes an OpenAPI specification for easy integration. The service is containerized with Docker for ease of deployment and scalability.

## Table of Contents

- [Description](#description)
- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [Credits](#credits)
- [How to Contribute](#how-to-contribute)

## Installation

To get started with this project, you need to install the required dependencies and run it. Follow these steps:

1. Clone the repository to your local machine.

```bash
git clone <repository_url>
```

2. Navigate to the project directory.

```bash
cd <project_directory>
```

3. Build the Docker image.

```bash
docker build -t weather-stations-api .
```

4. Run the Docker container.

```bash
docker run -p 8000:80 -d weather-stations-api
```

## Usage

## API Endpoints

1. **List all weather stations**
* Endpoint: GET '/api/weather-stations'
* Description: Returns a list of all weather stations with 'Station_id' and 'Name'.
* Authentication: Bearer token required.

2. **Get weather station details**
* Endpoint: GET '/api/weather-stations/{stationId}'
* Description: Returns detailed information for a specific weather station identified by 'Station_id'.
* Authentication: Bearer token required.

## Authentication

Use a Bearer token for authentication. Add the following header to your requests:
```bash
Authorization: Bearer <your_token>
```
## Access the API Documentation

The API documentation is available at http://localhost:8000/api/doc after running the Docker container.

## Features

* REST API: Exposes endpoints for listing all weather stations and getting details of a specific station.
* Authentication: Uses HTTP bearer preshared key authentication.
* OpenAPI Specification: Provides an OpenAPI specification for easy integration and testing.
* Dockerized: Includes a Dockerfile for easy deployment.

## Credits

This project uses data from the [Latvian Open Data Portal](https://data.gov.lv/dati/lv/dataset/hidrometeorologiskie-noverojumi/resource/c32c7afd-0d05-44fd-8b24-1de85b4bf11d).

## How to Contribute

Contributions to this project are welcome. To contribute, follow these steps:

1. Fork the repository to your GitHub account.
2. Create a new branch for your feature or bug fix.

```bash
git checkout -b feature/your-feature-name
```

3. Make your changes and commit them.

```bash
git add .
git commit -m "Added a new feature"
```

4. Push your changes to your forked repository.

```bash 
git push origin feature/your-feature-name
```

5. Create a Pull Request (PR) to the main repository, explaining your changes and improvements.