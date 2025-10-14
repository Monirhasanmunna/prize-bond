import Main from "@/Layouts/Backend/Main.jsx";
import {RxUpload} from "react-icons/rx";
import Button from "@/Components/Utils/Button/Button.jsx";
import {HSOverlay} from "preline";
import {useForm, usePage} from "@inertiajs/react";
import {useEffect, useRef, useState} from "react";

export default function Page({data:content}){
    const {hero_section} = content
    const {fileBase} = usePage().props

    const {data, setData, post, processing, errors, reset} = useForm({
        title: '',
        image: '',
        page_name: 'home'
    });
    const uploadFile = useRef(null);
    const [previewImage, setPreviewImage] = useState('');

    useEffect(() => {
        if(hero_section){
            setPreviewImage(`${fileBase}/${hero_section.image}`)
            setData({
                ...data,
                title: hero_section.title ?? '',
                id: hero_section.id ?? '',
            })
        } else {
            setData({
                title: '',
                image: '',
                page_name: 'home'
            })
            setPreviewImage(null)
        }
    }, [hero_section])

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
        post(route('admin.home.hero.update'), {
            preserveState: true,
            preserveScroll: true,
        })
    }

    return (
        <Main>
            <div className="w-full p-4 rounded bg-gray-100 shadow">
                <ol className="flex items-center whitespace-nowrap">
                    <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                        Dashboard
                        <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                        </svg>
                    </li>
                    <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                        Home
                        <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                        </svg>
                    </li>
                    <li className="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                        Hero Section
                    </li>
                </ol>
            </div>
            <div className="flex items-center justify-between border-b border-gray-300 p-5 border border-gray-300 rounded mt-5">
                <div className="w-full">
                    <form onSubmit={handleSubmitForm} className={`w-full space-y-2`}>
                        <div className="form-control">
                            <label htmlFor="title" className={`label`}>Title <span className={`text-xs text-red-600`}>*</span></label>
                            <input type="text" id={`title`} value={data.title} onChange={handleTextInput} className={`input`} placeholder={`Enter title`}/>
                        </div>

                        <div className="form-control">
                            <label htmlFor="image" className={`label`}>Image <span className={`text-xs text-red-600`}>*</span></label>
                            <div onClick={handleCLickInput} className="w-full flex justify-center items-center border h-[250px] input cursor-pointer relative overflow-hidden bg-white">
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
                                <Button buttonText={!hero_section ? 'Create' : 'Update'} isLoading={processing} />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </Main>
    )
}
