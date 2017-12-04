# pg-local_content
Local Content Management Module for RACHEL with support for lesson plans, which link content across modules.

## Motivation
The goals of the this RACHEL module are:
1. Improve access to existing content through the creation of lesson plans that group pointers to content across modules
2. Allow for post-install content upload, e.g. by a teacher or volunteer
3. Enable non-technical volunteer entry points aligned with their interests / expertise

## Usage
With this module, a RACHEL admin can WYSIWYG create a new module that will be visible on RACHEL's homepage. 
See a demo video here (somewhat dated): http://piensagrande.org/video/LC-Demo-Video.mp4 

## Developer Notes
Lesson plans are stored as json files so they could be created and stored via a web resource and deployed later. 

## Future Direction
This module was originally designed to enable local teachers and volunteers to improve their local system; however, we'd like to share locally-created modules globally. Future versions will enable broader sharing (perhaps listing dependencies, i.e. other modules referenced), as well as support for remote volunteers to create lessons plans via a web-hosted RACHEL image. 

## Strategic notes
From an AI in education standpoint, we need more and more ways of tracking a learning path (or order of operations) for learning a subject with available resources.  The lesson plan json objects represent a critical step of saving teacher intelligence for how resources should be grouped.
