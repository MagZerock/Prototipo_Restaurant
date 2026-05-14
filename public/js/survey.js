document.addEventListener('DOMContentLoaded', function() {
    const surveyForm = document.querySelector('form[action="index.php?action=survey"]');
    if (surveyForm) {
        surveyForm.addEventListener('submit', function(e) {
            const customer = surveyForm.querySelector('input[name="customer"]').value.trim();
            const comment = surveyForm.querySelector('textarea[name="comment"]').value.trim();
            const rating = surveyForm.querySelector('input[name="rating"]:checked');
            
            if (!rating) {
                e.preventDefault();
                alert('Por favor, seleccione una calificación.');
            } else if (!customer || !comment) {
                e.preventDefault();
                alert('Por favor, complete todos los campos de la encuesta.');
            }
        });
    }
});
