import ReactQuill from 'react-quill';
import 'react-quill/dist/quill.snow.css';

export default function TextEditor({ value, setValue }) {
    const modules = {
        toolbar: [
            [{ 'header': [1, 2, false] }],
            ['bold', 'italic', 'underline', 'strike', 'blockquote'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' },'link'],
            [{ 'align': [] }], // ✅ alignment
            [{ 'color': [] }], // ✅ text color & background
        ]
    };

    const formats = [
        'header',
        'bold', 'italic', 'underline', 'strike', 'blockquote',
        'list', 'bullet',
        'align',
        'color', 'background',
        'link', 'image'
    ];

    return (
        <ReactQuill
            value={value}
            onChange={setValue}
            modules={modules}
            formats={formats}
            className="custom-editor"
        />
    );
}
