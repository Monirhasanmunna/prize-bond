import React from "react";
import {LuFileSpreadsheet} from "react-icons/lu";

const ExcelUploader = ({ selectedFile, onFileChange }) => {
    const handleClick = () => {
        document.getElementById("fileInput").click();
    };

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file && (file.name.endsWith(".xlsx") || file.name.endsWith(".xls"))) {
            onFileChange(file);
        } else {
            alert("Please upload a valid Excel file (.xlsx or .xls)");
            onFileChange("");
        }
    };

    const removeFile = (e) => {
        e.stopPropagation();
        onFileChange("");
    };

    return (
        <div
            className="flex flex-col items-center justify-center h-[200px] w-full border-2 border-dashed border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition cursor-pointer relative"
            onClick={handleClick}
        >
            <input
                id="fileInput"
                type="file"
                accept=".xlsx, .xls"
                onChange={handleFileChange}
                className="hidden"
            />

            {selectedFile ? (
                <div className="flex flex-col items-center space-y-3">
                    <LuFileSpreadsheet size={60} className="text-green-600" />
                    <p className="text-gray-700 font-medium text-center px-4">
                        {selectedFile.name}
                    </p>
                    <p className="text-sm text-gray-400">
                        {(selectedFile.size / 1024).toFixed(1)} KB
                    </p>

                    <button
                        onClick={removeFile}
                        className="absolute top-3 right-3 bg-red-500 text-white rounded-full w-6 h-6 hover:bg-red-600 transition"
                    >
                        X
                    </button>
                </div>
            ) : (
                <div className="flex flex-col items-center space-y-3">
                    <LuFileSpreadsheet size={60} className="text-gray-400" />
                    <p className="text-gray-600 font-medium">
                        Click to upload Excel file
                    </p>
                    <p className="text-sm text-gray-400">(Supported: .xlsx, .xls)</p>
                </div>
            )}
        </div>
    );
};

export default ExcelUploader;
