const dots = document.getElementById('dots');
if (dots) {
    let dotCount = 0;

    setInterval(() => {
        dotCount++;
        if (dotCount > 3) dotCount = 0;
        dots.textContent = '.'.repeat(dotCount);
    }, 500);
}




const faqButtons = document.querySelectorAll('.faq-question');
if (faqButtons.length > 0) {
    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const answer = button.nextElementSibling;
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
                answer.style.paddingTop = "0";
                answer.style.paddingBottom = "0";
            } else {
                answer.style.maxHeight = answer.scrollHeight + "px";
                answer.style.paddingTop = "15px";
                answer.style.paddingBottom = "15px";
            }
        });
    });
}




const ctaButton = document.querySelector('.cta.outline');
const resumeSection = document.getElementById('resume-section');

if (ctaButton && resumeSection) {
    ctaButton.addEventListener('click', () => {
        if (!document.querySelector('.resume-form')) {
            resumeSection.innerHTML = `
                <section class="resume-form-section">
                    <h2 class="resume-form-title">Start Building Your Resume</h2>
                    <p class="resume-form-body">
                        Enter details below to begin creating your professional resume.
                    </p>

                    <form class="resume-form" action="resume.php" method="POST">
                        <div class="form-group">
                            <input type="text" id="fname" name="fname" placeholder="First Name *" required>
                        </div>

                        <div class="form-group">
                            <input type="text" id="lname" name="lname" placeholder="Surname *" required>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email Address *" required>
                        </div>

                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Phone Number (optional)">
                        </div>

                        <button type="submit" class="resume-btn">Next Step</button>
                    </form>
                </section>
            `;
            resumeSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
}




document.addEventListener("DOMContentLoaded", () => {

    function toggleTextArea(checkboxName, textareaId) {
        const checkbox = document.querySelector(`input[name="${checkboxName}"]`);
        const textarea = document.getElementById(textareaId);

        if (!checkbox || !textarea) return;

        function updateState() {
            if (checkbox.checked) {
                textarea.disabled = true;
                textarea.style.backgroundColor = "#e5e5e5";
                textarea.style.cursor = "not-allowed";
            } else {
                textarea.disabled = false;
                textarea.style.backgroundColor = "white";
                textarea.style.cursor = "text";
            }
        }

        updateState();
        checkbox.addEventListener("change", updateState);
    }

    toggleTextArea("exclude_work", "work");
    toggleTextArea("exclude_education", "education");



    function setupLivePreview(textareaId, previewId, fallbackText) {
        const textarea = document.getElementById(textareaId);
        const preview = document.getElementById(previewId);

        if (!textarea) return;
        if (!preview) return;

        function updatePreview() {
            const value = textarea.value;

            preview.innerHTML = value
                ? value.replace(/\n/g, "<br>")
                : fallbackText;
        }

        updatePreview();

        textarea.addEventListener("input", updatePreview);
    }

    setupLivePreview("summary", "preview-summary", "Your summary will appear here...");
    setupLivePreview("work", "preview-work", "Your work experience will appear here...");
    setupLivePreview("education", "preview-education", "Your education details will appear here...");



    function setupSkillsPreview(textareaId, previewId, fallbackText) {
        const textarea = document.getElementById(textareaId);
        const preview = document.getElementById(previewId);

        if (!textarea) return;
        if (!preview) return;

        function updateSkills() {
            const skillsArray = textarea.value
                .split(/[\n,]+/)
                .map(skill => skill.trim())
                .filter(skill => skill.length > 0);

            if (skillsArray.length === 0) {
                preview.textContent = fallbackText;
            } else {
                preview.innerHTML = skillsArray
                    .map(skill => `• ${skill}`)
                    .join("<br>");
            }
        }

        updateSkills();
        textarea.addEventListener("input", updateSkills);
    }

    setupSkillsPreview("skills", "preview-skills", "• Your skills will appear here...");




    const fname = document.querySelector('input[name="fname"]');
    const lname = document.querySelector('input[name="lname"]');
    const email = document.querySelector('input[name="email"]');
    const phone = document.querySelector('input[name="phone"]');

    const previewName = document.getElementById("preview-name");
    const previewContact = document.getElementById("preview-contact");

    if (fname && lname && previewName) {
        function updateName() {
            previewName.textContent = `${fname.value} ${lname.value}`.trim();
        }
        updateName();
        fname.addEventListener("input", updateName);
        lname.addEventListener("input", updateName);
    }

    if (email && phone && previewContact) {
        function updateContact() {
            previewContact.innerHTML = `${email.value}<br>${phone.value}`;
        }
        updateContact();
        email.addEventListener("input", updateContact);
        phone.addEventListener("input", updateContact);
    }

});