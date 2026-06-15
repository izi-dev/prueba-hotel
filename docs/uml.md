# Diagramas UML

## Diagrama de clases (dominio)

```mermaid
classDiagram
    class Hotel {
        +int id
        +string name
        +string address
        +int cityId
        +string nit
        +int maxRooms
    }

    class HotelRoomConfiguration {
        +int id
        +int hotelId
        +int roomTypeId
        +int accommodationId
        +int quantity
    }

    class RoomType {
        +int id
        +string name
        +string slug
    }

    class Accommodation {
        +int id
        +string name
        +string slug
    }

    class City {
        +int id
        +string name
    }

    Hotel "1" --> "*" HotelRoomConfiguration
    Hotel --> City
    HotelRoomConfiguration --> RoomType
    HotelRoomConfiguration --> Accommodation
    RoomType "*" --> "*" Accommodation : reglas permitidas
```

## Diagrama de secuencia — Crear configuración de habitación

```mermaid
sequenceDiagram
    participant C as CreateRoomConfigurationController
    participant H as CreateRoomConfigurationHandler
    participant R as RoomTypeAccommodationRules
    participant R as HotelRoomConfigurationRepository

    C->>H: handle(command)
    H->>R: assertValid(roomTypeId, accommodationId)
    H->>R: existsByHotelRoomTypeAndAccommodation()
    H->>R: totalQuantityByHotelId()
    H->>R: save(configuration)
    R-->>H: HotelRoomConfiguration
    H-->>C: HotelRoomConfiguration
    C-->>Client: 201 JSON
```

## Diagrama de capas

```mermaid
flowchart TB
    subgraph Presentation
        Controllers
        Requests
        Resources
    end

    subgraph Application
        Handlers
        Commands
    end

    subgraph Domain
        Entities
        RepositoryInterfaces
        DomainServices
    end

    subgraph Infrastructure
        EloquentModels
        EloquentRepositories
    end

    Controllers --> Handlers
    Handlers --> RepositoryInterfaces
    Handlers --> DomainServices
    EloquentRepositories -.-> RepositoryInterfaces
    EloquentRepositories --> EloquentModels
```

## Patrones aplicados

- **Repository**: abstrae persistencia en `Domain`, implementación en `Infrastructure`
- **Use Case / Command Handler**: un handler por operación de negocio
- **Single Responsibility**: un controlador por acción HTTP
- **Dependency Inversion**: handlers dependen de interfaces, no de Eloquent
