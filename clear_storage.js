
(function () {
    const companyId = {{ Auth:: user() -> company_id
}};
console.log('Clearing localStorage for company:', companyId);

// Clear relevant keys
localStorage.removeItem(`proposalCustomTemplates_${companyId}`);
localStorage.removeItem(`proposalNextId_${companyId}`);
localStorage.removeItem(`deletedDefaultTemplates_${companyId}`);

// Also clear keys that might have been created without companyId during dev
localStorage.removeItem('proposalCustomTemplates');
localStorage.removeItem('proposalNextId');
localStorage.removeItem('deletedDefaultTemplates');

alert('Local storage cleared for templates. Reloading...');
location.reload();
}) ();
