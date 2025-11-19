
import json

with open('/home/ubuntu/agroperto/agroperto-final/routes.json', 'r') as f:
    routes_data = json.load(f)

api_routes = []
for route in routes_data:
    if route['uri'].startswith('api/'):
        api_routes.append(route)

with open('/home/ubuntu/agroperto/agroperto-final/api_routes.json', 'w') as f:
    json.dump(api_routes, f, indent=4)

print(f'Found {len(api_routes)} API routes. Saved to api_routes.json')

