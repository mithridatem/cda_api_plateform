# TP — API Platform : création d'endpoints personnalisés

**Projet support :** [cda_api_plateform](https://github.com/mithridatem/cda_api_plateform)

## Objectifs pédagogiques

- Déclarer plusieurs opérations de lecture sur une même ressource.
- Personnaliser les URI des endpoints.
- Implémenter des State Providers et utiliser l'injection de dépendances.
- Réutiliser les Repository et le QueryBuilder.
- Maîtriser les données exposées par les groupes de sérialisation.
- Sécuriser, documenter et tester les opérations.

---

## Exercice 1 — Récupérer les catégories par ordre alphabétique

**Difficulté : facile**

### Contexte

Un client front-end souhaite récupérer la liste des catégories triées par ordre alphabétique afin d'alimenter un menu de navigation.

### Endpoint attendu

`GET /api/categories/alphabetical`

### Travail à réaliser

1. Créer une méthode dans `CategoryRepository` permettant de récupérer les catégories triées par nom dans l'ordre croissant.
2. Créer une classe `CategoryAlphabeticalProvider` implémentant `ProviderInterface`.
3. Injecter le Repository dans le Provider.
4. Déclarer une nouvelle opération `GetCollection` dans l'attribut `ApiResource` de l'entité `Category`.
5. Associer le Provider à cette opération.
6. Configurer les groupes de sérialisation.
7. Documenter l'opération dans Swagger UI.

### Exemple de réponse attendue

```json
[
    { "id": 3, "name": "Fantasy" },
    { "id": 1, "name": "Histoire" },
    { "id": 2, "name": "Science-fiction" }
]
```

### Critères de validation

- [ ] Le statut HTTP retourné est `200`.
- [ ] Les catégories sont triées par ordre alphabétique.
- [ ] Une collection vide est retournée si aucune catégorie n'existe.
- [ ] Les opérations existantes restent fonctionnelles.

---

## Exercice 2 — Récupérer les livres d'une catégorie

**Difficulté : intermédiaire**

### Contexte

Un utilisateur souhaite consulter uniquement les livres appartenant à une catégorie précise.

### Endpoint attendu

`GET /api/categories/{id}/books`

Exemple : `GET /api/categories/3/books`

### Travail à réaliser

1. Créer une méthode dans `BookRepository` permettant de récupérer les livres associés à une catégorie à partir de son identifiant.
2. Utiliser le QueryBuilder et la relation entre les entités `Book` et `Category`.
3. Créer une classe `CategoryBooksProvider`.
4. Récupérer l'identifiant de la catégorie depuis le tableau `$uriVariables` du Provider.
5. Déclarer une opération `GetCollection` personnalisée sur la ressource `Category`.
6. Configurer les métadonnées de l'opération pour indiquer que les éléments retournés sont des livres (`output` et variables d'URI).
7. Définir les groupes de sérialisation adaptés.
8. Documenter le paramètre `id` et les réponses possibles dans Swagger UI.

### Exemple de réponse attendue

```json
[
    { "id": 1, "title": "Le Hobbit", "author": "J.R.R. Tolkien" },
    { "id": 4, "title": "Le Seigneur des anneaux", "author": "J.R.R. Tolkien" }
]
```

### Critères de validation

- [ ] Une catégorie existante retourne un statut `200`.
- [ ] Seuls les livres associés à la catégorie sont retournés.
- [ ] Une catégorie sans livre retourne une collection vide.
- [ ] Une catégorie inexistante retourne un statut `404`.

### Question de réflexion

Pourquoi faut-il distinguer une catégorie inexistante d'une catégorie existante ne contenant aucun livre ?

---

## Exercice 3 — Récupérer les livres de l'utilisateur authentifié

**Difficulté : avancée**

### Contexte

Un utilisateur connecté souhaite consulter uniquement les livres qu'il a lui-même ajoutés dans la bibliothèque.

L'identité de l'utilisateur doit être déterminée à partir du contexte d'authentification JWT.

### Endpoint attendu

`GET /api/books/my-books`

### Travail à réaliser

1. Créer une méthode dans `BookRepository` permettant de récupérer les livres associés à un utilisateur.
2. Créer une classe `MyBooksProvider` implémentant `ProviderInterface`.
3. Injecter le service `Security` et le Repository `BookRepository` dans le Provider.
4. Récupérer l'utilisateur authentifié.
5. Récupérer uniquement les livres associés à cet utilisateur.
6. Déclarer une nouvelle opération `GetCollection` dans l'entité `Book`.
7. Restreindre l'accès aux utilisateurs authentifiés, notamment avant l'exécution du Provider via la configuration de sécurité.
8. Configurer les groupes de sérialisation.
9. Documenter l'opération dans Swagger UI.
10. Tester l'endpoint en transmettant un JWT valide.

### Exemple de requête

```http
GET /api/books/my-books
Authorization: Bearer <JWT>
Accept: application/json
```

### Critères de validation

- [ ] Un utilisateur authentifié reçoit un statut `200`.
- [ ] Seuls ses propres livres sont retournés.
- [ ] Un utilisateur ne possédant aucun livre reçoit une collection vide.
- [ ] Une requête sans JWT reçoit un statut `401`.
- [ ] Une requête avec un JWT invalide reçoit un statut `401`.
- [ ] Aucune donnée sensible de l'utilisateur n'est exposée.

### Contraintes de sécurité

- Ne pas transmettre l'identifiant de l'utilisateur dans l'URL.
- Ne pas décoder manuellement le JWT.
- Utiliser le contexte de sécurité de Symfony.
- Ne jamais retourner les livres appartenant à un autre utilisateur.

### Question de réflexion

Pourquoi est-il préférable de récupérer l'utilisateur depuis le contexte d'authentification plutôt que de permettre au client de transmettre son identifiant ?

---

## Bonus — Tests automatisés

Pour chaque exercice :

- Réaliser un test unitaire du Provider en mockant ses dépendances.
- Réaliser un test fonctionnel de l'endpoint avec `ApiTestCase`.
- Tester les scénarios nominaux et alternatifs.
- Vérifier les statuts HTTP.
- Vérifier le contenu et la structure des réponses JSON.

---

## Contraintes générales

- Respecter la séparation des responsabilités.
- Utiliser l'injection de dépendances.
- Ne pas écrire de requête Doctrine directement dans les Providers.
- Conserver les opérations API existantes.
- Vérifier que les routes statiques ne rentrent pas en conflit avec les routes contenant un identifiant dynamique.
- Utiliser le format `application/json` pour obtenir les représentations illustrées dans les exercices.
- Documenter chaque nouvelle opération.

## Livrables

- Code source des Providers.
- Méthodes des Repository.
- Configuration des opérations API Platform.
- Documentation accessible depuis Swagger UI.
- Tests automatisés pour les stagiaires ayant réalisé le bonus.

## Progression des compétences

| Compétence | Ex. 1 | Ex. 2 | Ex. 3 |
|---|:---:|:---:|:---:|
| Endpoint personnalisé | ✓ | ✓ | ✓ |
| State Provider | ✓ | ✓ | ✓ |
| Injection de dépendances | ✓ | ✓ | ✓ |
| QueryBuilder | ✓ | ✓ | ✓ |
| Relation entre entités | | ✓ | ✓ |
| Variables d'URI | | ✓ | |
| Gestion des erreurs | | ✓ | ✓ |
| Contexte de sécurité | | | ✓ |
| Prévention des IDOR | | | ✓ |
| Swagger UI | ✓ | ✓ | ✓ |
