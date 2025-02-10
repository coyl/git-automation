# gitAutomation

PHP application to automate git routines

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Contributing](#contributing)
- [License](#license)

## Introduction

`gitAutomation` is a PHP application designed to automate various git routines, making it easier to manage and streamline your git workflow.

## Features

- Automate common git tasks
- Integration with Jira for issue tracking
- Customizable queries and filters
- Detailed analysis and reporting

## Installation

To install the application, follow these steps:

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/gitAutomation.git
    ```

2. Navigate to the project directory:
    ```sh
    cd gitAutomation
    ```

3. Install the dependencies using Composer:
    ```sh
    composer install
    ```

## Usage

To use the application, run the following command:

```sh
php bin/console sprint:estimate-analysis [options]
```

### Options

- `--sprint`: Specify the sprint to analyze
- `--excludeLabels`: Exclude issues with specific labels
- `--additionalDql`: Add additional DQL filters
- `--assignees`: Filter issues by assignees

## Configuration

You can configure the application by editing the configuration files located in the `config` directory. Make sure to set up your Jira API credentials and other necessary settings.

## Contributing

We welcome contributions to improve this project. To contribute, please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature-branch`)
3. Make your changes
4. Commit your changes (`git commit -am 'Add new feature'`)
5. Push to the branch (`git push origin feature-branch`)
6. Create a new Pull Request

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.
```