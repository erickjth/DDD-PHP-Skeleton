# Application

he application layer contains classes called commands and command handlers. A command represents something that has to be done. It's a simple Data Transfer Object, containing only primitive type values and simple lists of those. There's always a command handler that knows how to process a particular command. Usually the command handler (which is also known as an application service) performs any orchestration needed. It uses the data from the command object to create an aggregate, or fetch one from the repository, and perform some action on it. It then often persists the aggregate.

Code in this layer could be unit tested, but having an application layer is also a good starting point for writing acceptance tests, as Gherkin scenarios (and run them with a tool like Behat). An interesting article to start with on this topic is Modelling by Example by Konstantin Kudryashov.
