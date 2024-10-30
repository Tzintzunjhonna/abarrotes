export default function setFormData(form) {
    const formData = new FormData();

    for (const [key, value] of Object.entries(form)) {
        if (typeof value === "object" && value !== null) {
            if (key == "photo_input") {
                formData.append(key, value);
            } else if (key == "tasa_cuota_porcentage") {
                formData.append(key, value.codigo);
            } else {
                formData.append(key, value.id);
            }
        } else {
            formData.append(key, value);
        }
    }

    return formData;
}
