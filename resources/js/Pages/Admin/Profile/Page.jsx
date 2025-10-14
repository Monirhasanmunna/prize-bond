import Main from "@/Layouts/Backend/Main.jsx";
import {RxUpload} from "react-icons/rx";
import Button from "@/Components/Utils/Button/Button.jsx";
import {HSOverlay} from "preline";
import {useForm, usePage} from "@inertiajs/react";
import {useEffect, useRef, useState} from "react";

export default function Page({data:profileContent}){
    const {profile} = profileContent

    const {data, setData, post, processing, errors, reset} = useForm({
        name: '',
        email: '',
        password: '',
        confirm_password: '',
    });

    useEffect(() => {
        if(profile){
            setData({
                ...data,
                name: profile.name ?? '',
                email: profile.email ?? '',
            })
        } else {
            setData({
                name: '',
                email: '',
                password: '',
                confirm_password: ''
            })
        }
    }, [profile])

    const handleTextInput = (e) => {
        const {id,value} = e.target

        setData(prevState => ({
            ...prevState,
            [id]: value
        }))
    }

    const handleSubmitForm = (e) => {
        e.preventDefault();
        post(route('admin.profile.update'), {
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
                        Profile
                    </li>
                </ol>
            </div>
            <div className="flex items-center justify-between border-b border-gray-300 p-5 border border-gray-300 rounded mt-5">
                <div className="w-full">
                    <form onSubmit={handleSubmitForm} className={`w-full space-y-2`}>
                        <div className="form-control">
                            <label htmlFor="name" className={`label`}>Name <span className={`text-xs text-red-600`}>*</span></label>
                            <input type="text" id={`name`} value={data.name} onChange={handleTextInput} className={`input`} placeholder={`Enter name`}/>
                        </div>

                        <div className="form-control">
                            <label htmlFor="email" className={`label`}>Email <span className={`text-xs text-red-600`}>*</span></label>
                            <input type="email" id={`email`} value={data.email} onChange={handleTextInput} className={`input`} placeholder={`Enter email`}/>
                        </div>

                        <div className="form-control">
                            <label htmlFor="password" className={`label`}>Password</label>
                            <input type="password" id={`password`} value={data.password} onChange={handleTextInput} className={`input`} placeholder={`*******`}/>
                        </div>

                        <div className="form-control">
                            <label htmlFor="confirm_password" className={`label`}>Confirm Password</label>
                            <input type="password" id={`confirm_password`} value={data.confirm_password} onChange={handleTextInput} className={`input`} placeholder={`********`}/>
                        </div>

                        <div className="form-control pt-4">
                            <div className="w-full flex gap-x-4">
                                <Button buttonText={!profile ? 'Create' : 'Update'} isLoading={processing} />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </Main>
    )
}
