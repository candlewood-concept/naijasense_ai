# naijasense_ai
An agentic behavioral intelligence platform for contextual review simulation and personalized recommendation.
---

## Overview

NaijaSense AI is an AI-powered behavioral intelligence platform that models users as dynamic behavioral agents rather than static recommendation profiles.

Traditional recommendation systems rely heavily on collaborative filtering and historical interactions while ignoring:

- emotional state
- environmental context
- budget constraints
- delivery sensitivity
- cultural behavior
- real-time decision conditions

NaijaSense AI addresses these limitations using contextual reasoning workflows powered by Large Language Models (LLMs), behavioral signal analysis, and agentic orchestration.

---

# Core Features

## AI Review Simulation

Generates realistic user reviews and ratings conditioned on:

- user personality
- budget style
- mood
- location
- delivery expectations
- contextual environment

Example:

> “Food sweet but delivery delay small. Portion for this price fit better.”

---

## Context-Aware Recommendations

Produces personalized recommendations using:

- weather conditions
- traffic conditions
- emotional state
- budget constraints
- convenience expectations
- delivery speed
- user preferences

---

## Behavioral Intelligence Layer

The platform extracts and reasons over behavioral signals including:

- price sensitivity
- comfort-food preference
- delivery consciousness
- convenience prioritization
- value-for-money expectations

---

## Explainable AI Reasoning

Every recommendation includes:

- reasoning traces
- contextual explanations
- confidence scores
- behavioral alignment signals

---

## Analytics Dashboard

Provides:

- simulation analytics
- recommendation analytics
- confidence metrics
- top behavioral signals
- AI reasoning observability

---

# System Architecture

```text
Flutter Web Dashboard
        ↓
Laravel API Gateway
        ↓
────────────────────
Persona Agent
Behavior Agent
Recommendation Agent
Review Simulation Agent
Evaluation Agent
────────────────────
        ↓
Groq LLM API
        ↓
MySQL Database
```

---

# Technology Stack

## Frontend
- Flutter Web
- Riverpod
- Material UI

## Backend
- Laravel
- PHP
- REST APIs

## AI Infrastructure
- Groq LLM APIs
- Agentic reasoning workflows

## Database
- MySQL

---

# API Endpoints

## Review Simulation

```http
POST /api/simulate-review
```

### Example Payload

```json
{
  "persona": {
    "name": "Tunde",
    "age": 22,
    "location": "Yaba, Lagos",
    "budget_style": "student_budget",
    "preferences": ["cheap food", "large portions"]
  },
  "item": {
    "name": "Mama Nkechi Kitchen",
    "category": "food",
    "price_level": 2
  }
}
```

---

## Recommendation Endpoint

```http
POST /api/recommend
```

### Example Payload

```json
{
  "persona": {
    "name": "Ada",
    "age": 31,
    "location": "Lekki",
    "budget_style": "premium"
  },
  "context": {
    "mood": "tired after work",
    "weather": "rainy",
    "budget": 10000
  },
  "items": [
    {
      "name": "Chicken Republic",
      "category": "food"
    }
  ]
}
```

---

## Analytics Endpoint

```http
GET /api/analytics
```

Returns:
- simulation metrics
- recommendation metrics
- confidence scores
- behavioral signal analytics
- reasoning traces

---

# Nigerian Contextualization

NaijaSense AI incorporates localized behavioral intelligence patterns commonly observed in Nigerian consumer environments, including:

- traffic-aware reasoning
- value-for-money sensitivity
- delivery trust concerns
- comfort-food preference patterns
- localized linguistic adaptation
- budget-conscious purchasing behavior

---

# Example Behavioral Signals

- `price_sensitive`
- `delivery_conscious`
- `comfort_food_preference`
- `budget_aware`

---

# Explainability

The platform prioritizes explainable AI reasoning by exposing:

- recommendation rationale
- contextual signal weighting
- confidence estimation
- reasoning traces

This improves transparency and behavioral observability.

---

# Running The Backend

## Laravel

```bash
composer install
php artisan migrate
php artisan serve
```

---

# Flutter Web

```bash
flutter pub get
flutter run -d chrome
```

---

# Environment Variables

```env
GROQ_API_KEY=
GROQ_MODEL=llama-3.3-70b-versatile
```

---

# Future Improvements

Planned future improvements include:

- long-term memory persistence
- reinforcement learning adaptation
- multimodal reasoning
- vector embeddings
- adaptive behavioral memory
- cross-domain recommendation systems

---

# Team

## Candlewood Concept

AI Engineering, Backend Architecture, Flutter Frontend, Behavioral Intelligence Design.

---

# Competition

Built for the DSN × BCT Agentic AI Challenge.

---

# License

MIT License
