TrampFramework
==============

For this framework I wanted to make everything as simple as possible for the Developer and automate as much as possible
while allowing the Developer to override anything very simply.

Requirements
------------
1. Controller and Action/Method directly linked to the URL - DONE
2. Server side datatable created with 1 line call
3. RESTfull
4. Template can be switched out by the config or the URL host (no template engine, but might add in Twig)
5. Automatic CRUD control
6. Automatic Add/Edit form using Form builder with 1 line
7. Automatic View for the index of any Controller
8. Builders for Queries, Tables, Forms and Menu
9. API sending json that is only usable from a URL matching a token - DONE
10. Template and all views based on Twitter Bootstrap


To Use
------
1. Create a Controller in app/Controllers and extend Controller
2. Go to /admin/CONTROLLER-NAME in the browser
3. Add a Method to the Controller
4. Go to /admin/CONTROLLER-NAME/METHOD-NAME in the browser
5. Change the method


See https://trello.com/b/759oYvdQ/tramp-framework
