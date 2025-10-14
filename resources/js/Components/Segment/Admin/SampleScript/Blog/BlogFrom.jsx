import {FaCross} from "react-icons/fa";
import {RxCross2, RxUpload} from "react-icons/rx";
import {useEffect, useRef, useState} from "react";
import {useForm, usePage} from "@inertiajs/react";
import {HSOverlay} from "preline";
import Button from "@/Components/Utils/Button/Button.jsx";
import TextEditor from "@/Components/Utils/TextEditor/TextEditor.jsx";

export default function BlogForm({blog, setBlog}){
    const {fileBase} = usePage().props
    const {data, setData, post, processing, errors, reset} = useForm({
        title: '',
        description: '',
        image: ''
    });
    const uploadFile = useRef(null);
    const [previewImage, setPreviewImage] = useState('');

    useEffect(() => {
        if(blog){
            setPreviewImage(`${fileBase}/${blog.image}`)
            setData({
                ...data,
                title: blog.title ?? '',
                description: blog.description ?? '',
                id: blog.id ?? ''
            })
        } else {
            setData({
                title: '',
                description: '',
                image: ''
            })
            setPreviewImage(null)
        }
    }, [blog])

    const handleCLickInput = () => {
        if(uploadFile.current){
            uploadFile.current.click();
        }
    }

    const handleTextInput = (e) => {
        const {id,value} = e.target

        setData(prevState => ({
            ...prevState,
            [id]: value
        }))
    }


    const handleFileInput = (e) => {
        const {files} = e.target
        const file = files[0];
        if(file){
            setPreviewImage(URL.createObjectURL(file))
            setData(prevState => ({
                ...prevState,
                image: file
            }))
        }
    }


    const handleSubmitForm = (e) => {
        e.preventDefault();

        if(!blog){
            post(route('admin.sample-script.blog.store'), {
                preserveState: true,
                preserveScroll: true,
                onError: () => HSOverlay.open('#blog-form'),
                onSuccess: () => {
                    HSOverlay.close('#blog-form')
                    reset()
                    setBlog(null)
                    setPreviewImage(null)
                }
            })
        }else{
            post(route('admin.sample-script.blog.update'), {
                preserveState: true,
                preserveScroll: true,
                onError: () => HSOverlay.open('#blog-form'),
                onSuccess: () => {
                    HSOverlay.close('#blog-form')
                    reset()
                    setBlog(null)
                    setPreviewImage(null)
                }
            })
        }
    }

    return (
        <div id="blog-form"
             className="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
             role="dialog" tabIndex="-1" aria-labelledby="blog-form-label">
            <div
                className="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
                <div
                    className="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-sm pointer-events-auto dark:bg-neutral-800 dark:border-neutral-600 dark:shadow-neutral-700/70">
                    <div
                        className="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-600">
                        <h3 id="blog-form-label" className="font-bold text-gray-800 text-md dark:text-white">
                            {blog ? 'Update': 'Create'} Form
                        </h3>
                        <button type="button"
                                onClick={(e) => {
                                    reset()
                                    setPreviewImage(null)
                                }}
                                className="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-200 dark:focus:bg-neutral-600"
                                aria-label="Close" data-hs-overlay="#blog-form">
                            <RxCross2 className={`size-4`} />
                        </button>
                    </div>
                    <div className="p-4 overflow-y-auto">
                        <form onSubmit={handleSubmitForm}  className={`w-full space-y-2`}>
                            <div className="form-control">
                                <label htmlFor="title" className={`label`}>Title <span className={`text-xs text-red-600`}>*</span></label>
                                <TextEditor value={data.title} setValue={(value) =>setData(prev => ({...prev, title: value}))} />
                            </div>

                            <div className="form-control">
                                <label htmlFor="title" className={`label`}>Description <span className={`text-xs text-red-600`}>*</span></label>
                                <TextEditor value={data.description} setValue={(value) =>setData(prev => ({...prev, description: value}))} />
                            </div>

                            <div className="form-control">
                                <label htmlFor="name" className={`label`}>Image <span className={`text-xs text-red-600`}>*</span></label>
                                <div onClick={handleCLickInput} className="w-full flex justify-center items-center border h-[150px] input cursor-pointer relative overflow-hidden bg-white">
                                    {
                                        !previewImage ? (
                                            <div className="flex flex-col items-center gap-y-3 z-10">
                                                <RxUpload className="size-10 opacity-[0.5]" />
                                                <span className="text-gray-400 animate-pulse">Upload File Here</span>
                                            </div>
                                        ) : (
                                            <div className="w-full">
                                                <img src={previewImage} className={`w-full h-full object-center`} alt="image"/>
                                            </div>
                                        )

                                    }
                                    <input ref={uploadFile} onChange={handleFileInput} type="file" className="hidden" />
                                </div>
                            </div>

                            <div className="form-control pt-4">
                                <div className="w-full flex gap-x-4">
                                    <Button buttonText={!blog ? 'Create' : 'Update'} isLoading={processing} />
                                    <Button
                                        buttonText={'Cancel'}
                                        type={`button`}
                                        callback={() => HSOverlay.close("#blog-form")}
                                        className={`bg-red-500 text-white rounded hover:bg-red-600 duration-150`}
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}
