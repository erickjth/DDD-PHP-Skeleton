# Infraestructure

The infrastructure layer contains any code that is needed to expose the use cases to the world and make the application communicate with real users and external services. Think of anything that gives your domain model and your application services "hands and feet" and actually makes the use cases of your application "usable". This layer contains the code for:

Processing HTTP requests, producing a response for an incoming request
Making (HTTP) requests to other servers
Storing things in a database
Sending emails
Publishing messages
Getting the current timestamp
Generating random numbers
This kind of code requires integration testing (in the terminology of Freeman and Pryce). You test all the "real things": the real database, the real vendor code, the real external services involved. This allows you to verify all the assumptions your infrastructure code makes about things that are beyond your control.

Frameworks and libraries
Any framework and library that is related to "the world outside" (e.g. networking, file systems, time, randomness) will be used or called in the infrastructure layer. Of course, code in the domain and application layers need the functionality offered by ORMs, HTTP client libraries, etc. But they can only do so through more abstract dependencies. This gets dictated by the dependency rule.
