# Weather
Reading and saving weather data from Oregon Scientific RAR213HG weather station

# Installation

### Using composer:

#### 1. Edit composer.json

> By running command in terminal
> composer require hollanbo/weather

OR

> Add to required list of packages in composer.json:  
> "hollanbo/weather": "^1.0"

#### 2. Add to list of providers in config/app.php:
> hollanbo\Weather\WeatherServiceProvider::class
