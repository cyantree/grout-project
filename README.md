cyantree Grout-Project
======================

Changes
-------

### 0.1.1

-   **BUG:** phpunit.xml referenced to wrong tests directory.

### 0.1.0

-   **FEATURE:** Added basic support for unit testing with PHPUnit

### 0.0.7

-   **CHANGE:** Updated module versions of "Cyantree\ErrorReportingModule" and
    "Cyantree\WebConsoleModule"

### 0.0.6

-   **CHANGE:** GlobalFactory. appTask() now uses DataStorage.

### 0.0.5

-   **BREAKING:** Removed all Cyantree-Modules from repository. Please use
    composer to install the needed modules.

-   **FEATURE:** Implemented DataStorage logic

-   **FEATURE:** Added WebConsoleModule and created SetupCommand to install
    project on new system.

-   **FEATURE:** Added GlobalTemplateContext.

-   **FEATURE:** Added GlobalPage which provides a shortcut to the
    GlobalFactory.

### 0.0.4

-   **CHANGE:** Added charset "UTF-8" to Doctrine configuration.
