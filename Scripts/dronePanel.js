    const deleteLinks = document.querySelectorAll(".delete-link");
    deleteLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete this drone?")) {
                window.location.href = this.href;
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".status-dropdown").forEach(function (dropdown) {
            dropdown.addEventListener("change", function () {
                let droneId = this.getAttribute("data-id");
                let newStatus = this.value;
    
                fetch("update_status.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `id=${droneId}&status=${encodeURIComponent(newStatus)}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
    