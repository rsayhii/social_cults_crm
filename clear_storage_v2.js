
(function () {
    const companyId = {{ Auth:: user() -> company_id
}};
console.log('Clearing localStorage for company:', companyId);

const keys = [
    `proposalCustomTemplates_${companyId}`,
    `proposalNextId_${companyId}`,
    `deletedDefaultTemplates_${companyId}`,
    `proposalCustomTemplates_${companyId} `, // with space
    `proposalNextId_${companyId} `,           // with space
    `deletedDefaultTemplates_${companyId} `   // with space
];

keys.forEach(key => localStorage.removeItem(key));

console.log('Cleanup complete.');
}) ();
