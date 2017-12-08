<?php namespace pg_local_content; ?>
<?php

global $lang1;

// default english
$templ["disk_space_avail"] = "Available disk space";
$templ["create_module"] = "Create New Module";
$templ["no_space_for_new"] = "Insufficient disk space to create new module.";
$templ["create_lesson"] = "Create a new lesson";
$templ["pglc_preview_title"] = "Title of your module";
$templ["pglc_preview_description"] = "Description of your module.";
$templ["lesson"] = "Lesson";
$templ["your_content"] = "Create a new module with your content";
$templ["step1"] = "Add an image, title, and description for your module. (See preview above.)";
$templ["step2"] = "Upload static and dynamic content for your module on the next page.";
$templ["step3"] = "Create lessons for your module.";
$templ["just_image"] = "Image";
$templ["select_sample"] = "Select an image";
$templ["choose_image"] = "or upload an image";
$templ["just_title"] = "Title";
$templ["just_description"] = "Description";
$templ["just_link"] = "Link";
$templ["make_module"] = "Make Module";
$templ["not_image"] = "is not an image";
$templ["not_img_type"] =  "is not JPG, JPEG, PNG, SVG or GIF. Only these file types are allowed";
$templ["large_file"] = "is too large";
$templ["just_module"] = "Module";
$templ["unique_title"] = "already exists. Please select a new title";
$templ["fix_errors"] = "Please fix the following errors";
$templ["required"] = "is required";
$templ["special_chars"] = "Please eliminate the special characters";
$templ["allowed_chars_title"] = "Only alphamumeric characters, underscores, and dashes are allowed in the title";
$templ["jq_filesize_p1"] = "File is too large to upload. (Permitted";
$templ["jq_filesize_p2"] = "KB. Your file is";
$templ["no_upload_space"] = "There is not sufficient disk space to upload this image";
$templ["upload_your_content"] = "Upload your content!";
$templ["file_to_upload"] = "File to upload";
$templ["select"] = "Select";
$templ["content_type"] = "Content type";
$templ["static_type"] = "Document or Static Content";
$templ["dynamic_type"] = "Video or Interactive Content";
$templ["static_types"] = "Documents and Static Content";
$templ["dynamic_types"] = "Videos and Interactive Content";
$templ["upload"] = "Upload";
$templ["delete"] = "Delete";
$templ["error"] = "Error";
$templ["preview"] = "Preview";
$templ["save"] = "Save";
$templ["new"] = "New";
$templ["lesson_title"] = "Title of Lesson or Group";
$templ["objective"] = "Objective";
$templ["critical_points"] = "Critical Points";
$templ["teaching_tips"] = "Teaching Tips";
$templ["optional"] = "Optional";
$templ["finished"] = "Finished";
$templ["local_content"] = "Local Content";
$templ["saved"] = "Saved!";
$templ["are_you_sure"] = "Are you sure?";
$templ["title_uri_required"] = "Title and Link are required";
$templ["lesson_title_required"] = "Lesson title is required";
$templ["lesson_title_unique"] = "A lesson with this title already exists. Please use a new title";
$templ["forbidden_type"] = "File type not permitted.";
$templ["forbidden_first_char"] = "File names must begin with [A-Z or 0-9].";
$templ["forbidden_same_name"] = "File name already exists.  Please change the name of your file.";
$templ["delete_element"] = "Delete Element";

// override with language translations when available
switch ($lang1) {
	case ("es"):
		$templ["disk_space_avail"] = "Espacio disponible en el disco";
		$templ["create_module"] = "Crear Un Nuevo Módulo";
		$templ["no_space_for_new"] = "Insuficiente espacio en disco para crear un nuevo módulo.";
		$templ["create_lesson"] = "Crear una nueva lección";
		$templ["pglc_preview_title"] =  "Título de su módulo";
		$templ["pglc_preview_description"] = "Descripción de su módulo.";
		$templ["lesson"] = "Lección";
		$templ["your_content"] = "Crear Un Nuevo Módulo de Su Contenido";
		$templ["step1"] = "Crear su nuevo módulo de aquí con una imagen, título, y una descripción. (Ver el vista previa arriba.)";
		$templ["step2"] = "Cargar su contenido estático e interactivo a su módulo en la página siguiente.";
		$templ["step3"] = "Crear lecciones con su contenido dentro de su módulo.";
		$templ["just_image"] = "Imagen";
		$templ["select_sample"] = "Elige un muestra";
		$templ["choose_image"] = "o elige suyo";
		$templ["just_title"] = "Título";
		$templ["just_description"] = "Descripción";
		$templ["just_link"] = "Enlace";
		$templ["make_module"] = "Hacer Nuevo Módulo";
		$templ["not_image"] = "no es una imagen";
		$templ["not_img_type"] = "no es de tipo JPG, JPEG, PNG, SVG o GIF"; 
		$templ["large_file"] = "es demasiado grande";
		$templ["just_module"] = "Módulo";
		$templ["unique_title"] = "ya existe. Elija un nuevo título";
		$templ["fix_errors"] = "Arreglar los siguientes errores";
		$templ["required"] = "es requerido";
		$templ["special_chars"] = "Por favor, elimina los caracteres especiales"; 
		$templ["allowed_chars_title"] = "Sólo caracteres alfanuméricos, _ y - están permitidos en el Título";
		$templ["jq_filesize_p1"] = "Archivo es demasiado grande para cargar. (Se permiten";
		$templ["jq_filesize_p2"] = "KB. Suyo es";
		$templ["no_upload_space"] = "No hay suficiente espacio en el disco para cargar";
		$templ["upload_your_content"] = "¡Carga su contenido!";
		$templ["file_to_upload"] = "Archivo para cargar";
		$templ["select"] = "Seleccione";
		$templ["content_type"] = "Tipo de contenido";
		$templ["static_type"] = "Documento o Contenido Estático";
		$templ["dynamic_type"] = "Vídeo o Contenido Interactivo";
		$templ["static_types"] = "Documentos y Contenido Estático";
                $templ["dynamic_types"] = "Vídeos y Contenido Interactivo";
		$templ["upload"] = "Cargar";
		$templ["delete"] = "Borrar";
		$templ["error"] = "Error";
		$templ["preview"] = "Preestreno";
		$templ["save"] = "Guardar";
		$templ["new"] = "Nuevo";
		$templ["lesson_title"] = "Título de Lección o Grupo";
		$templ["objective"] = "Objetivo";
		$templ["critical_points"] = "Puntos Críticos";
		$templ["teaching_tips"] = "Consejos para la Enseñanza";
		$templ["optional"] = "Opcional";
		$templ["finished"] = "Acabado";
		$templ["local_content"] = "Contenido Local";
		$templ["saved"] = "¡Se guardó!";
		$templ["are_you_sure"] = "¿Seguro?";
		$templ["title_uri_required"] = "Título y Enlace necesitan valores";
		$templ["lesson_title_required"] = "Se requiere título de la lección";
		$templ["lesson_title_unique"] = "Ya existe una lección con este título. Usa un nuevo título";
		$templ["forbidden_type"] = "No se permite este tipo de archivo.";
		$templ["forbidden_first_char"] = "Los nombres de archivos tienen que comenzar con [A-Z o 0-9].";
		$templ["forbidden_same_name"] = "El nombre del archivo ya existe. Por favor, cambiar el nombre de su archivo.";
		$templ["delete_element"] = "Borrar Elemento";
		break;
	// can support additional languages via extra cases 
}

?>
