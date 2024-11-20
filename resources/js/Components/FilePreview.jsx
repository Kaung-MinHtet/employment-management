import React from "react";
import { useDropzone } from "react-dropzone";

const FilePreview = ({ file, setFile, onFileChange }) => {
    const onDrop = (acceptedFiles) => {
        if (acceptedFiles.length > 0) {
            const selectedFile = acceptedFiles[0];
            const fileWithPreview = Object.assign(selectedFile, {
                preview: URL.createObjectURL(selectedFile),
            });

            // setFile(fileWithPreview); // Update local state
            onFileChange(fileWithPreview); // Notify parent component
            // console.log(fileWithPreview)
        }
    };

    const { getRootProps, getInputProps } = useDropzone({
        onDrop,
        accept: "image/*",
        maxFiles: 1, // limit to one file
    });

    return (
        <div>
            <div
                {...getRootProps()}
                style={{
                    border: "2px dashed #ccc",
                    padding: "20px",
                    textAlign: "center",
                }}
            >
                <input {...getInputProps()} />
                <p>Drag & drop some files here, or click to select files</p>
            </div>
            <div style={{ display: "flex", gap: "10px", marginTop: "20px" }}>
                {file && (
                    <div>
                        <p>
                            {file.name} ({(file.size / 1024).toFixed(2)} KB)
                        </p>
                        <img
                            src={file.preview}
                            alt={file.name}
                            style={{
                                width: "100px",
                                height: "100px",
                                objectFit: "cover",
                            }}
                        />
                    </div>
                )}
            </div>
        </div>
    );
};

export default FilePreview;
