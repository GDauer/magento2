# Magento 2.3.6 Magento Commerce Cloud

This repository contains a sample Magento Commerce (on-premise) version 2.3.6 instance for you to deploy in the cloud. You must have an active Magento Commerce Cloud user license to use the example in this repository.

The example requires the use of [Composer](https://getcomposer.org/doc/) to load and manage dependencies and Magento vendor folders.

-  [Authentication](#authentication)
    -  [Authenticating in Docker](#authenticating-in-docker)
-  [Repository structure](#repository-structure)
-  [Developer documentation](#developer-documentation)

## Authentication

You must have an authentication key to access the Magento Commerce repository and to enable install and update commands for your Magento Commerce Cloud project. 
The following method is best to prevent accidental exposure of credentials, such as pushing an `auth.json` file to a public repository. If you plan to use Docker for your local development, then jump to the [Authenticating in Docker](#authenticating-in-docker) section.

To add authentication keys using an environment variable:

1.  In the _Project Web UI_, click the configuration icon in the upper left corner.

1.  In the _Configure Project_ view, click the **Variables** tab.

1.  Click **Add Variable**.

1.  In the _Name_ field, enter `env:COMPOSER_AUTH`.

1.  In the _Value_ field, add the following and replace `<public-key>` and `<private-key>` with your Magento Commerce Cloud authentication credentials.

    ```json
    {
       "http-basic": {
          "repo.magento.com": {
          "username": "<public-key>",
          "password": "<private-key>"
        }
      }
    }
    ```

1.  Select **Visible during build** and deselect **Visible at run**.

1.  Click **Add Variable**.

See [Adding Magento authentication keys](https://devdocs.magento.com/cloud/setup/first-time-setup-import-prepare.html#auth-json).

### Authenticating in Docker

You must have an `auth.json` file that contains your Magento Commerce authorization credentials in your Magento Commerce Cloud root directory.

1.  Using a text editor, create an `auth.json` file and save it in your Magento root directory.

1.  Replace <public-key> and <private-key> with your Magento Commerce authentication credentials.

    ```json
    {
      "http-basic": {
        "repo.magento.com": {
          "username": "<public-key>",
          "password": "<private-key>"
        }
      }
    }
    ```

1.  Save your changes to `auth.json` file and exit the text editor.

To use Docker for local development, see [Launching a Docker configuration](https://devdocs.magento.com/cloud/docker/docker-config.html).

## Repository structure

The following is a list of the specific files required for this example to work in the Magento Commerce Cloud:

```bash
.magento/
        /routes.yaml
        /services.yaml
.magento.app.yaml
auth.json
composer.json
magento-vars.php
php.ini
```

-  `.magento/routes.yaml`—redirects `www` to the naked domain and `php` application to serve HTTP.
-  `.magento/services.yaml`—sets up a MySQL instance, including Redis and ElasticSearch. 
-  `composer.json`—fetches the Magento Enterprise Edition and configuration scripts to prepare your application.

## DOCKER LOCAL DEPLOYMENT ##

According to the operating system you use, you must install the following tools:

** LINUX Debian/Ubuntu/Centos. **

 1. Install Git according to your distribution.
 2. Docker [latest version](https://docs.docker.com/engine/install/).
 3. Docker Compose [latest version](https://docs.docker.com/compose/install/#install-compose).
 4. Set permissions to run Docker with your user, avoiding doing it with root. To do this, run the command "sudo groupadd docker" and then "sudo usermod -aG docker $USER" replacing $USER with your username.

IMPORTANT: Avoid permission problems, DO NOT run these tasks as root.

** Mac OSx **

 1. Install Git via Xcode.
 2. Docker [latest version](https://docs.docker.com/docker-for-mac/install/).
 3. In Docker "Settings", verify that you are using gRPC FUSE for the shared file system and that the VM uses at least 6GB of RAM and 1GB of SWAP. Make sure you have at least 12GB of RAM on your Mac, otherwise you will lose significant performance.

IMPORTANT: It is recommended to use Mac computers from the year 2018 onwards, on previous computers it is possible to present performance problems that make the container deployment unfeasible.

** WINDOWS 10 Home/Professional. **

 1. Update Windows to its latest version [IMPORTANT].
 2. Download a Linux image from Microsoft Store [Ubuntu 20.04](https://www.microsoft.com/es-cl/p/ubuntu-2004-lts/9n6svws3rx71?activetab=pivot:overviewtab), from this image and its terminal you will manage Docker and other resources. Start the Ubuntu-20.04 terminal and choose a username and password, then close the terminal (We will reopen it later).
 3. Install Docker [latest version](https://docs.docker.com/engine/install/), in the wizard you must select the box to install WSL2. After installation it is necessary to restart the computer.
 4. Start Docker and in "Setting > General" verify that it does NOT start when Windows starts, this will help avoid performance problems on the computer. Then go to "Setting> Resorces> WSL INTEGRATION" and enable the Ubuntu-20.04 image.
 5. Check that the integration was done correctly by executing the Ubuntu-20.04 terminal and the following command "docker ps". If the command fails, check the previous steps.
 6. In your code editor install an extension or plugins compatible with WLS, this will allow you to interact with the files within the Ubuntu terminal, for example, Visual Studio Code [Remote WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl). When installing the extension, a green button "> <" will be created on the left side of VSCode, click and a browser will open where you must select "Remote-WSL: New Window".

OPTIONAL: To access and view the files directly in Windows it is necessary to create a network drive. In Windows Explorer enter the following path without brackets [ \\\wsl$ ], another explorer will open where you must right-click on the Ubuntu-20.4 drive, and then click on "Connect to network drive ..." . This action will create a shortcut to the files of the Ubuntu machine in "This Computer" under the disk "C:". By default the project files inside the Linux machine are in /home/[user]. So far no project has been cloned, therefore the folder will be empty.

IMPORTANT: Avoid permission problems, DO NOT run these tasks as root.

----------

After installing the tools, follow the steps below to run a local deploy.

 1. Clone the project in local machine.
 2. Download [media bases images](https://mega.nz/file/MBlyGLCY#_O52UvTWsz5k26ydVJ4lEplNHsV4lhwgEdQsCnj7KaA), unzip and paste in pub/media.
 3. Launch a local deploy:

`bash start-docker.sh local`   

IMPORTANT: When executing the project for the first time, it may take between 10 and 15 min, because it requires downloading the containers, libraries and loading the initial database.

**Access url**

Frontend: https://local.mitocondria.cl:8080   
Backend: https://local.mitocondria.cl:8080/MadMax/   
user: admin   
pass: admin123   

## Developer documentation

See the [Magento Commerce Cloud Guide](https://devdocs.magento.com/cloud/bk-cloud.html).

## License
Each Magento source file included in this distribution is licensed under the OSL-3.0 license.

Please see [LICENSE.txt](https://github.com/magento/magento-cloud/blob/master/LICENSE.txt) for the full text of the [Open Software License v. 3.0 (OSL-3.0)](http://opensource.org/licenses/osl-3.0.php).
