import {useEffect, useRef, useState} from "react";
import {usePage} from "@inertiajs/react";
import {RxCross2} from "react-icons/rx";

export default function ImageUploader({setData, images, id, removeImage, className = null, isEdit = null, previewImage = null, isOldImage = false}){
    const {fileBase} = usePage().props
    const [isHover, setIsHover] = useState(false)
    const [preview, setPreview] = useState('');
    const fileRef = useRef(null);

    useEffect(() => {
        if (isEdit && previewImage) {
            setPreview(fileBase + '/' + previewImage);
        }
    }, [previewImage]);

    const handleFileClick = () => {
        if(fileRef.current){
            fileRef.current.click()
        }
    }

    const handleFileInput = (e) => {
        const file = e.target.files[0];
        if(file){
            const updatedImages = images.map((img) =>
                img.id.toString() === id.toString() ? { ...img, file: file } : img
            );

            setData('images', updatedImages);
            const previewUrl = URL.createObjectURL(file);
            setPreview(previewUrl);
        }
    }


    return (
        <div className="form-control w-full h-full">
            <label htmlFor="image" className="label">
                Image <span className="text-sm text-red-600">*</span>
            </label>

            <div
                onClick={handleFileClick}
                onMouseEnter={() => setIsHover(true)}
                onMouseLeave={() => setIsHover(false)}
                className={`w-full h-full border border-gray-400 rounded-[2px] flex items-center justify-center text-gray-500 dark:text-gray-400 dark:bg-[#4C5056] text-sm cursor-pointer relative ${isHover ? 'bg-stripes animate-stripes' : ''}`}
            >
                {
                    images.length > 1 ? (
                        <button
                            type='button'
                            onClick={(e) => {e.stopPropagation(); isOldImage ? removeImage(id, null) : removeImage(null, id)}}
                            className={`h-[20px] w-[20px] flex items-center justify-center bg-red-600 text-white absolute top-[-8px] right-[-8px] text-[18px] rounded-full cursor-pointer`}>
                            <RxCross2 className={`size-4`} />
                        </button>
                    ) : (
                        <></>
                    )
                }
                {
                    preview ? (
                            <div className='preview w-full h-full'>
                                <img src={preview} alt="" className='w-full h-full object-fill'/>
                            </div>
                        ) : (
                            <span>Upload File</span>
                        )
                }
                <input type="file" className={`hidden`} ref={fileRef} onChange={handleFileInput}/>
            </div>
        </div>
    )
}
