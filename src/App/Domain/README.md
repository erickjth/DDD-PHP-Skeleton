# Domain

The domain layer contains classes of any of the familiar DDD types/design patterns:

- Entities
- Value objects
- Domain events
- Repositories
- Domain services
- Factories
- ...

Within Domain I create a subdirectory Model, then within Model another directory for each of the aggregates that I model. An aggregate directory contains all the things related to that aggregate (value objects, domain events, a repository interface, etc.).

Domain model code is ethereal as I like to call it. It has no touching points with the real world. And if it were not for the tests, no one would call this code yet (it happens in the higher layers). Tests for domain model code can be purely unit tests, as all they do is execute code in memory. There is no need for domain model code to reach out to the world outside (like approaching the file system, making a network call, generate a random number or get the current time). This makes its t
