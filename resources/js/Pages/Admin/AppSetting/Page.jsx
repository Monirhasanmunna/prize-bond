import Main from "@/Layouts/Backend/Main.jsx";
import {RxUpload} from "react-icons/rx";
import Button from "@/Components/Utils/Button/Button.jsx";
import {HSOverlay} from "preline";
import {useForm, usePage} from "@inertiajs/react";
import {useEffect, useRef, useState} from "react";
import {FaUpload} from "react-icons/fa";
import {deepUpdate} from "@/helpers/helper.js";

export default function Page({data:appSettingData}){
    const {fileBase} = usePage().props

    const {data, setData, processing, errors, post} = useForm({
        app_name: {
            content: ''
        },
        favicon: {
            path: '',
        },
        logo: {
            path: ''
        },
        contact_info: {
            email: '',
            phone: '',
            skype: ''
        },
        social_links: {
            linkedin: '',
            facebook: '',
            instagram: ''
        },
        copyright: {
            content: ''
        }
    });


    useEffect(() => {
        setData((prev) => {
            const updated = {...prev}
            const safeParse = (key) => {
                const setting = appSettingData.appSetting.find((s) => s.key === key);
                return setting?.value ? JSON.parse(setting.value) : null;
            };

            Object.keys(prev).forEach((key) => {
                const value = safeParse(key);
                if (value) {
                    updated[key] = deepUpdate(prev[key], value);
                }
            });

            return updated;

        })
    }, []);

    const handleInputText = (e) => {
        const { id, value, name } = e.target;

        setData(prevState => ({
            ...prevState,
            [name]: {
                ...prevState[name],
                [id]: value
            }
        }));
    };




    const handleFileChange = (e) => {
        const { id, files } = e.target;
        const file = files[0];

        if (id === 'logo') {
            setData(prev => ({
                ...prev,
                logo: {
                    ...prev.logo,
                    file
                }
            }));
        }
        else if (id === 'favicon') {
            setData(prev => ({
                ...prev,
                favicon: {
                    file
                }
            }));
        }
    };


    const submit = (e) => {
        e.preventDefault();
        post(route('admin.app-setting.update'), {
            preserveState:true,
            preserveScroll:true
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
                        App Setting
                    </li>
                </ol>
            </div>
            <form onSubmit={submit}>
                <div className="w-full border border-gray-300 rounded mt-5">
                    <div className="w-full bg-gray-600 border-b border-gray-300 px-5 py-3">
                        <h1 className={`text-lg text-gray-200`}>General Setting :</h1>
                    </div>
                    <div className="w-full p-5">
                        <div className="w-full mb-5">
                            <div className="form-control">
                                <label className={`label`} htmlFor="app_name">App Name :</label>
                                <input type="text" value={data.app_name?.content} onChange={handleInputText} id='content' name={`app_name`} placeholder={`Enter frontend name`} className={`input`}/>
                            </div>
                        </div>
                        <div className="w-full flex justify-start gap-10">
                            <div className="form-control !w-auto">
                                <label htmlFor="logo" className={`label`}>Logo</label>
                                <label htmlFor="logo">
                                    <div className="flex flex-col items-center justify-center w-24 h-24 overflow-hidden border border-gray-400">
                                        <div className={`w-full h-24 flex items-center justify-center ${data.logo?.path ? 'object-cover' : ''}`}>
                                            {
                                                data.logo?.file ? (
                                                    <img
                                                        src={URL.createObjectURL(data.logo?.file)}
                                                        alt="Uploaded Logo"
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : data.logo.path ? (
                                                    <img
                                                        src={fileBase + '/' + data.logo.path}
                                                        alt="Uploaded Favicon"
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : (
                                                    <FaUpload className="size-9 text-gray-500" />
                                                )
                                            }
                                        </div>
                                        <input
                                            onChange={handleFileChange}
                                            type="file"
                                            id="logo"
                                            hidden
                                        />
                                    </div>
                                </label>
                            </div>
                            <div className="form-control !w-auto">
                                <label htmlFor="logo" className={`label`}>Favicon</label>
                                <label htmlFor="favicon">
                                    <div className="flex flex-col items-center justify-center w-24 h-24 overflow-hidden border border-gray-400">
                                        <div className={`w-full h-24 flex items-center justify-center ${data.favicon?.path ? 'object-cover' : ''}`}>
                                            {
                                                data.favicon?.file ? (
                                                    <img
                                                        src={URL.createObjectURL(data.favicon?.file)}
                                                        alt="Uploaded Favicon"
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : data.favicon.path ? (
                                                    <img
                                                        src={fileBase + '/' + data.favicon.path}
                                                        alt="Uploaded Favicon"
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : (
                                                    <FaUpload className="size-9 text-gray-500" />
                                                )
                                            }
                                        </div>
                                        <input
                                            onChange={handleFileChange}
                                            type="file"
                                            id="favicon"
                                            hidden
                                        />
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="w-full flex flex-col md:flex-row border border-gray-300 rounded mt-5">
                    <div className="w-full md:w-[50%] border-r border-gray-300">
                        <div className="w-full bg-gray-600 border-b border-gray-300 px-5 py-3">
                            <h1 className={`text-lg text-gray-200`}>Contact Info :</h1>
                        </div>
                        <div className="w-full p-4">
                            <div className="w-full space-y-3">
                                <div className="form-control">
                                    <label className={`label`} htmlFor="email">Email :</label>
                                    <input type="email" value={data.contact_info.email} onChange={handleInputText} id='email' name={`contact_info`} placeholder={`Enter email`} className={`input`}/>
                                </div>
                                <div className="form-control">
                                    <label className={`label`} htmlFor="phone">Phone :</label>
                                    <input type="text" value={data.contact_info.phone} onChange={handleInputText} id='phone' name={`contact_info`} placeholder={`Enter phone`} className={`input`}/>
                                </div>
                                <div className="form-control">
                                    <label className={`label`} htmlFor="skype">Skype :</label>
                                    <input type="text" value={data.contact_info.skype} onChange={handleInputText} id='skype' name={`contact_info`} placeholder={`Enter skype`} className={`input`}/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="w-full md:w-[50%]">
                        <div className="w-full bg-gray-600 border-b border-gray-300 px-5 py-3">
                            <h1 className={`text-lg text-gray-200`}>Social Links :</h1>
                        </div>
                        <div className="w-full p-5">
                            <div className="w-full space-y-3">
                                <div className="form-control">
                                    <label className={`label`} htmlFor="linkedin">Linkedin :</label>
                                    <input type="text" value={data.social_links?.linkedin} onChange={handleInputText} id='linkedin' name={`social_links`} placeholder={`Enter linkedin`} className={`input`}/>
                                </div>
                                <div className="form-control">
                                    <label className={`label`} htmlFor="facebook">Facebook :</label>
                                    <input type="text" value={data.social_links?.facebook} onChange={handleInputText} id='facebook' name={`social_links`} placeholder={`Enter facebook`} className={`input`}/>
                                </div>
                                <div className="form-control">
                                    <label className={`label`} htmlFor="instagram">Instagram :</label>
                                    <input type="text" value={data.social_links?.instagram} onChange={handleInputText} id='instagram' name={`social_links`} placeholder={`Enter instagram`} className={`input`}/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="form-control mt-3">
                    <label className={`label`} htmlFor="content">Copyright :</label>
                    <input type="text" value={data.copyright.content} onChange={handleInputText} id='content' name={`copyright`} placeholder={`Enter copyright`} className={`input`}/>
                </div>
                <div className="form-control pt-4">
                    <div className="w-full flex gap-x-4">
                        <Button buttonText={`Update`} isLoading={processing} />
                    </div>
                </div>
            </form>
        </Main>
    )
}
