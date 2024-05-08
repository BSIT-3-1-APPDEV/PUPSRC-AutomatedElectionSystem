<a name="readme-top"></a>

<div align="start">
  <h1>iVOTE: PUPSRC Online Election System :ballot_box: :bar_chart:</h1>
</div>

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![Pull requests][pull-requests-shield]][pull-requests-url]


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem">
    <img src="src/images/resc/iVOTE4.png" alt="Logo" width="300" height="120">
  </a>

  <div align="start">
      <h2>About</h2>
      <ul>
        <li>iVOTE is an under-development online voting web application for Polytechnic University of the Philippines Santa Rosa Campus Student Academic Organizations.</li>
        <li>This project is for partial fulfillment of the subject COMP 20133: Applications Development and Emerging Technologies.</li>
      </ul>
  </div>
</div>



<!-- TABLE OF CONTENTS -->
<h2>Table of Contents</h2>
<ol>
  <li><a href="#tech-stack">Tech Stack</a></li>
  <li><a href="#features">Features</a></li>
  <li>
    <a href="#installation">Installation</a>
    <ul>
      <li><a href="#prerequisites">Prerequisites</a></li>
    </ul>
  </li>
  <li><a href="#role-access">Role Access</a></li>
  <li><a href="#for-collaborators">For Collaborators</a></li>
  <li><a href="#acknowledgments">Acknowledgments</a></li>
  <li><a href="#contributors">Contributors</a></li>
  <li><a href="#ui-snippets">UI Snippets</a></li>
  <li><a href="#recommendation">Recommendation</a></li>
</ol>


## Tech Stack
Tech stack and frameworks used to build iVOTE web-application

* [![Bootstrap][Bootstrap.com]][Bootstrap-url]
* [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)][PHP-url]
* [![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)][CSS-url]
* [![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white)][HTML-url]
* [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)][JavaScript-url]
* [![SCSS](https://img.shields.io/badge/SCSS-CC6699?style=for-the-badge&logo=sass&logoColor=white)][SCSS-url]
* [![JQuery][JQuery.com]][JQuery-url]



<!-- GETTING STARTED -->
## Features

<details><summary>Features Offered:</summary>
<ol>
  <li>Electronic Ballot Form</li>
  <li>Live Counting of Results</li>
  <li>
    Election configuration
    <ul>
      <li>Add Ballot Form Input Field</li>
      <li>Vote Scheduling</li>
      <li>Dynamic Voting Guidelines</li>
      <li>Candidates Sequence on Ballot Form</li>
    </ul>
  </li>
  <li>Archive of Election Results</li>
  <li>Exportable Reports (pdf, xlsx, csv, and docx)</li>
  <li>Candidates Information Management</li>
  <li>Voters Account Mangagement</li>
  <li>Password Recovery</li>
  <li>Login Attempts Lockout</li>
</ol></details>

<details><summary>Other Features:</summary>
<ol>  
  <li>Mobile Responsive</li>
  <li>Full Screen Toggle of Live Results</li>
  <li>Anonymous Toggle of Live Results</li>
  <li>Email Notification about Account Approval or Rejection</li>
</ol></details>



<!-- INSTALLATION -->
## Installation

### Prerequisites
- You must have installed PHP and [Git](https://git-scm.com/downloads) on your machine.
- To access the phpMyAdmin, you need [XAMPP](http://localhost/phpmyadmin/index.php) installed also

1. Clone the repository by running this git command.
  * git
  ```sh
  git clone --single-branch --branch main https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem
  ```
_Note: Change the branch name as you see fit. In this case, the name of the checked out branch is main._




<!-- ROLE ACCESS -->
## Role Access

| Account Type | Features |
|--------|--------|
| Student-Voter | Account registration, password recovery, and vote casting. | 
| Admin | Validation of voter account, and access to live counting of results | 
| Head Admin | Similar to admin, with only the additional privilege of adding new admin accounts |




<!-- CONTRIBUTE -->
## For Collaborators
1. Clone this repository and checkout the `development` branch on your computer
 ```sh
  git clone --single-branch --branch development https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem
  ```
2. Create a local branch on your computer
  ```sh
  git checkout -b your_branch
  ```
3. Commit your changes
```sh
  git add .
  git commit -m "type: issue-key-your_commit_message"
```
4. If finished, push/publish your local branch to this repository
  ```sh
  git push origin your_branch
```
5. Open a pull request from your published branch to merge it into the `development` branch
6. Await the review and approval of at least one collaborator on your pull request
7. After approval, ensure that you pull the latest changes from the `development` branch on your computer.
  ```sh
  git pull origin development
```

_Important Note: Keep in mind the issue key of your Team's Project in Jira and proper conventional commit message._




<!-- ACKNOWLEDGMENTS -->
## Acknowledgments
The project team would like to thank the student academic organizations of PUPSRC below for their unwavering and continuous support and guidance for the success of this endeavor:
* [Student Council Organization](https://www.facebook.com/thepupsrcstudentcouncil)
* [Association of Competent and Aspiring Psychologists](https://www.facebook.com/ACAPpage)
* [Association of Electronics and Communications Engineering](https://www.facebook.com/OfficialAECES)
* [Eligible League of Information Technology Enthusiasts](https://www.facebook.com/ELITE.PUPSRC)
* [Guild of Imporous and Valuable Educators](https://www.facebook.com/educgive)
* [Junior Executives of Human Resource Association](https://www.facebook.com/PUPSRCJEHRA)
* [Junior Marketing Association of the Philippines](https://www.facebook.com/JMAPPUPSRCOfficial)
* [Junior Philippine Institute of Accountants](https://www.facebook.com/JPIA.PUPSRC)
* [Philippine Institute of Industrial Engineers](https://www.facebook.com/piiepup)

<!-- CONTRIBUTORS -->
## Contributors

<div align="center">
  <a href="https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/graphs/contributors">
    <img src="https://contrib.rocks/image?repo=BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem" />
  </a>
</div>

Made with [contrib.rocks](https://contrib.rocks).

## UI Snippets
- To follow


<!-- RECOMMENDATIONS -->
## Recommendation
You can read more about:

**Conventional Commits:** www.conventionalcommits.org/en/v1.0.0/#summary <br/>
**Semantic Versioning:** https://semver.org/


<p align="right"><a href="#readme-top">Back to Top</a></p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem.svg?style=for-the-badge
[contributors-url]: https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem.svg?style=for-the-badge
[forks-url]: https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/network/members
[stars-shield]: https://img.shields.io/github/stars/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem.svg?style=for-the-badge
[stars-url]: https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/stargazers
[issues-shield]: https://img.shields.io/github/issues/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem.svg?style=for-the-badge
[issues-url]: https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/issues
[pull-requests-shield]: https://img.shields.io/github/issues-pr/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem.svg?style=for-the-badge
[pull-requests-url]: https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem/pulls
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[PHP-url]: https://www.php.net
[CSS-url]: https://developer.mozilla.org/en-US/docs/Web/CSS
[HTML-url]: https://developer.mozilla.org/en-US/docs/Web/HTML
[JavaScript-url]: https://developer.mozilla.org/en-US/docs/Web/JavaScript
[SCSS-url]: https://sass-lang.com/documentation/syntax
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 
