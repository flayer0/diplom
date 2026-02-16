document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addTeacherCourseModal');
    
    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        const teacherId = button.getAttribute('data-teacher-id');
        const courseId = button.getAttribute('data-course-id');
        
        document.getElementById('modalTeacherId').value = teacherId;
        document.getElementById('modalCourseId').value = courseId;
    });
});
