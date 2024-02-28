import React, { useCallback, useState } from "react";
import { useDropzone } from "react-dropzone";
import { useTranslation } from "react-i18next";
import i18n from "../i18n";

const dropzoneStyle = {
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  justifyContent: "center",
  padding: "20px",
  borderWidth: "2px",
  borderRadius: "8px",
  borderColor: "#eeeeee",
  borderStyle: "dashed",
  color: "#aaaaaa",
  outline: "none",
  transition: "border 0.24s ease-in-out",
  cursor: "pointer",
  paddingTop: "20px",
};

const ImagePreview = {
  display: "flex",
  maxWidth: "100%",
  maxHeight: "100%",
  margin: "auto",
  borderRadius: "10px",
};

const DropzoneComponent = () => {
  const [files, setFiles] = useState([]);
  const onDrop = useCallback((acceptedFiles) => {
    setFiles(
      acceptedFiles.map((file) =>
        Object.assign(file, {
          preview: URL.createObjectURL(file),
        })
      )
    );
  }, []);

  const { getRootProps, getInputProps } = useDropzone({
    onDrop,
    accept: "image/*",
    maxFiles: 1
  });

  const { t } = useTranslation();

  const fileList = files.map((file) => (
    <li key={file.name}>
      <img style={ImagePreview} src={file.preview} alt={file.name} />
      <span>{file.name}</span>
    </li>
  ));

  return (
    <div id="dragdrop" style={dropzoneStyle}
      {...getRootProps()}>
      <input {...getInputProps()} />
      <p>{t("dragdrop_here")}</p>
      <ul>{fileList}</ul>
    </div>
  );
};

export default DropzoneComponent;