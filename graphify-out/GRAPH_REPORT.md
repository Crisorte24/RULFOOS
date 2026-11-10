# Graph Report - php pag rulfoos  (2026-11-09)

## Corpus Check
- Corpus is ~9,288 words - fits in a single context window. You may not need a graph.

## Summary
- 67 nodes · 133 edges · 7 communities (5 shown, 2 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Páginas de la tienda
- Carrito de compras
- Panel de administración
- API de inventario (Python)
- Login, sesión y conexión

## God Nodes (most connected - your core abstractions)
1. `e()` - 8 edges
2. `tabla productos` - 8 edges
3. `startVoiceNavigation()` - 7 edges
4. `guardarCarrito()` - 6 edges
5. `obt_inv()` - 5 edges
6. `flash()` - 4 edges
7. `calcularTotalCarrito()` - 4 edges
8. `dibujarCarritos()` - 4 edges
9. `dibujarCarritoMenu()` - 4 edges
10. `dibujarCarritoPago()` - 4 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Import Cycles
- None detected.

## Communities (7 total, 2 thin omitted)

### Community 0 - "Páginas de la tienda"
Cohesion: 0.23
Nodes (4): getRandomMessage(), speak(), startVoiceNavigation(), tabla productos

### Community 1 - "Carrito de compras"
Cohesion: 0.26
Nodes (13): agregarAlCarrito(), avisar(), calcularTotalCarrito(), cambiarCantidad(), carritoEnMemoria, dibujarCarritoMenu(), dibujarCarritoPago(), dibujarCarritos() (+5 more)

### Community 2 - "Panel de administración"
Cohesion: 0.40
Nodes (6): campoImagen, form, flash(), borrarImagenSiNoSeUsa(), guardarImagen(), tabla promociones

### Community 3 - "API de inventario (Python)"
Cohesion: 0.24
Nodes (3): conectar_bd(), enviar_correo(), obt_inv()

### Community 4 - "Login, sesión y conexión"
Cohesion: 0.31
Nodes (4): e(), btnApi, tabla contactos, tabla usuarios

## Knowledge Gaps
- **1 isolated node(s):** `tabla contactos`
  These have ≤1 connection - possible missing edges. (Counts symbols only; 21 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **2 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `tabla productos` connect `Páginas de la tienda` to `Panel de administración`, `API de inventario (Python)`, `Chatbot (sin usar)`?**
  _High betweenness centrality (0.271) - this node is a cross-community bridge._
- **What connects `tabla contactos` to the rest of the system?**
  _1 weakly-connected nodes found - possible missing edges._
- **Why does `calcularTotalCarrito()` connect `Carrito de compras` to `Páginas de la tienda`?**
  _High betweenness centrality (0.174) - this node is a cross-community bridge._
- **Why does `vaciarCarritoPago()` connect `Carrito de compras` to `Páginas de la tienda`?**
  _High betweenness centrality (0.135) - this node is a cross-community bridge._