// JavaScript Example: Reading Entities
// Filterable fields: company_name, contact_person, email, phone, status, industry, budget, source, notes, next_follow_up, priority
async function fetchClientEntities() {
    const response = await fetch(`https://app.base44.com/api/apps/68f137825233e9ff8fc5ea14/entities/Client`, {
        headers: {
            'api_key': 'b29ae2c6f3964da696e49d039a21f96a', // or use await User.me() to get the API key
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    console.log(data);
}

// JavaScript Example: Updating an Entity
// Filterable fields: company_name, contact_person, email, phone, status, industry, budget, source, notes, next_follow_up, priority
async function updateClientEntity(entityId, updateData) {
    const response = await fetch(`https://app.base44.com/api/apps/68f137825233e9ff8fc5ea14/entities/Client/${entityId}`, {
        method: 'PUT',
        headers: {
            'api_key': 'b29ae2c6f3964da696e49d039a21f96a', // or use await User.me() to get the API key
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(updateData)
    });
    const data = await response.json();
    console.log(data);
}