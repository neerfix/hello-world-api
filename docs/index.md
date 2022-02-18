# Les bonnes pratiques PHP du projet

## Base de données / Entités :
Les entités en base de donnée sont en `snake_case`.
Les variables en PHP sont en `camelCase`.

On n'utilise pas la structure générée de base par symfony : 
```php
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
```
mais plutôt :
```php
/*
 * @ORM\Column(name="id", type="string", length="180", unique=true)
 */
```

## PHP 8
Ne pas oublier les attributes des variables dans la déclaration des fonctions et le type retourné:
```php
public function success(int $status, ?array $data = null, ?array $pagination = null, ?array $information = null): JsonResponse
```

## Controller
Le controller ne vérifie que les accès, les autorisations, renvoie les données d'un repository pour un get ou le résultat d'un EntityService pour les requêtes `PUT`, `POST`, `PATCH`, `DELETE`.
En cas de requête d'édition de donnée, vérifier que ces données exists bien avant d'appeler le service.

## Services
Le service vérifie la valeur des entités et met à jour l'entité avant de faire un retour de celle-ci.
Le `UserService` doit modifier un user avec les valeurs avant de le retourner là où il est appelé. La fonction retourne donc son entité. 
