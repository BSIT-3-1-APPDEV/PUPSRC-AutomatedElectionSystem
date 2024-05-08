<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a name="readme-top"></a>
<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![Pull requests][pull-requests-shield]][pull-requests-url]


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem">
    <img src="src/images/resc/iVOTE4.png" alt="Logo" width="400" height="150">
  </a>

  <div align="start">
      <h2>About</h2>
      <ul>
        <li>iVOTE is an under-development online voting web application for Polytechnic University of the Philippines Santa Rosa Campus Student Academic Organizations.</li>
        <li>This project is for partial fulfillment of the subject COMP 20133: Applications Development and Emerging Technologies.</li>
      </ul>
  </div>
<!--     <br />
    <a href="https://github.com/github_username/repo_name"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/github_username/repo_name">View Demo</a>
    ·
    <a href="https://github.com/github_username/repo_name/issues/new?labels=bug&template=bug-report---.md">Report Bug</a>
    ·
    <a href="https://github.com/github_username/repo_name/issues/new?labels=enhancement&template=feature-request---.md">Request Feature</a> -->
</div>



<!-- TABLE OF CONTENTS -->

<h2>Table of Contents</h2>
<ol>
  <li><a href="#stack">Tech Stack</a></li>
  <li><a href="#features">Features</a></li>
  <li><a href="#installation">Installation</a></li>
  <li><a href="#role">Role Access</a></li>
  <li><a href="#contribute">Contributors/Collaborators</a></li>
  <li><a href="#acknowledgments">Acknowledgments</a></li>
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

<p align="right"><a href="#readme-top">back to top</a></p>



<!-- GETTING STARTED -->
## Features

<ol>
  <li>One-time use of vote account</li>
  <li>Live counting of vote results</li>
  <li>Responsive on mobile</li>
  <li>
    Election configuration
    <ul>
      <li>Ballot form optional inclusion of student personal information</li>
      <li>Scheduling of opening and closing of voting period</li>
      <li>Voting guidelines modification</li>
      <li>Arrangement and position of candidates on ballot forms</li>
    </ul>
  </li>
  <li>Archiving of previous election results</li>
  <li>Downloadable reports in PDF, XLSX, CSV, and DOCX extension</li>
  <li>Adding of candidates</li>
  <li>Approval and validation of voters account</li>
  <li>Password reset through email</li>
  <li>Account blocking after 5 consecutive login attempts</li>
</ol>


<p align="right"><a href="#readme-top">back to top</a></p>



<!-- INSTALLATION -->
## Installation

Prerequesites
- You must have installed PHP and [Git](https://git-scm.com/downloads) on your machine.
- To access the phpMyAdmin, you need [XAMPP](http://localhost/phpmyadmin/index.php) installed also

1. Clone the repository
  ```sh
  git clone --single-branch --branch main https://github.com/BSIT-3-1-APPDEV/PUPSRC-AutomatedElectionSystem
  ```
_Note: Change the branch name as you see fit. In this case, the name of the checked out branch is main._

<p align="right"><a href="#readme-top">back to top</a></p>



<!-- ROLE ACCESS -->
## Role Access

| Account Type | Features |
|--------|--------|
| Student-Voter | Account registration, password recovery, and vote casting. | 
| Admin | Validation of voter account, and access to live counting of results | 
| Head Admin | Similar to admin, with only the additional privilege of adding new admin accounts |

<p align="right"><a href="#readme-top">back to top</a></p>



<!-- CONTRIBUTE -->
## Contributors/Collaborators
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
<p align="right"><a href="#readme-top">back to top</a></p>



<!-- ACKNOWLEDGMENTS -->
## Acknowledgments
The project team would like to thank the student academic organizations below:
* [Student Council Organization](https://www.facebook.com/thepupsrcstudentcouncil)
* [Association of Competent and Aspiring Psychologists](https://www.facebook.com/ACAPpage)
* [Association of Electronics and Communications Engineering](https://www.facebook.com/OfficialAECES)
* [Eligible League of Information Technology Enthusiasts](https://www.facebook.com/ELITE.PUPSRC)
* [Guild of Imporous and Valuable Educators](https://www.facebook.com/educgive)
* [Junior Executives of Human Resource Association](https://www.facebook.com/PUPSRCJEHRA)
* [Junior Marketing Association of the Philippines](https://www.facebook.com/JMAPPUPSRCOfficial)
* [Junior Philippine Institute of Accountants](https://www.facebook.com/JPIA.PUPSRC)
* [Philippine Institute of Industrial Engineers](https://www.facebook.com/piiepup)

<!-- COLLABORATORS -->
### Collaborators

[<img src="https://github.com/Andrea-Villalobos.png" width="100px" height="100px">](https://github.com/Andrea-Villalobos)
[<img src="https://github.com/biellamariscotes.png" width="100px" height="100px">](https://github.com/biellamariscotes)
[<img src="https://github.com/Andrei-Matibag.png" width="100px" height="100px">](https://github.com/Andrei-Matibag)
[<img src="https://github.com/AraojoBenedict.png" width="100px" height="100px">](https://github.com/AraojoBenedict)
[<img src="https://github.com/C-Ivan-Bandilla.png" width="100px" height="100px">](https://github.com/C-Ivan-Bandilla)
[<img src="https://github.com/hatdogguldo.png" width="100px" height="100px">](https://github.com/hatdogguldo)
[<img src="https://github.com/Ivan-Edan.png" width="100px" height="100px">](https://github.com/Ivan-Edan)
[<img src="https://github.com/Jeremie-Legrama.png" width="100px" height="100px">](https://github.com/Jeremie-Legrama)
[<img src="https://github.com/Peter-Escueta.png" width="100px" height="100px">](https://github.com/Peter-Escueta)
[<img src="https://github.com/yojibeans.png" width="100px" height="100px">](https://github.com/yojibeans)
[<img src="https://github.com/Yoro-Izumi.png" width="100px" height="100px">](https://github.com/Yoro-Izumi)

<p align="right"><a href="#readme-top">back to top</a></p>


<!-- RECOMMENDATIONS -->
## Recommendation
You can read more about:

**Conventional Commits:** www.conventionalcommits.org/en/v1.0.0/#summary <br/>
**Semantic Versioning:** https://semver.org/

<p align="right"><a href="#readme-top">back to top</a></p>

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
