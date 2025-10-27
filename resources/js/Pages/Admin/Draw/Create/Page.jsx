import Main from "@/Layouts/Backend/Main.jsx";
import {Link, useForm} from "@inertiajs/react";
import ExcelUploader from "@/Components/Utils/ExcelUploader/ExcelUploader.jsx";
import Button from "@/Components/Utils/Button/Button.jsx";
import {FaArrowRight} from "react-icons/fa";

export default function Page(){
    const {data, setData, errors, processing, post} = useForm({
        name: '',
        date: '',
        file: ''
    })

    const handleInput = (e) => {
        const {id, value} = e.target

        setData(prev => ({
            ...prev,
            [id]: value
        }));
    }

    const handleFormSubmit = (e) => {
        e.preventDefault();
        post(route('admin.draw.store'))
    }

    return (
        <>
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
                            Draw
                            <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                            </svg>
                        </li>
                        <li className="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                            Create
                        </li>
                    </ol>
                </div>

                <div className="w-full border border-gray-300 rounded mt-5">
                    <div className="flex items-center justify-between border-b border-gray-300 px-5 py-3">
                        <div className="flex items-center gap-x-6">
                            <h2 className="font-medium text-xl leading-6 text-neutral-500 dark:text-neutral-300">Add New</h2>
                        </div>
                        <Link href={route(`admin.draw.list`)} className="py-1.5 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded bg-blue-600 text-white hover:bg-blue-700">
                           Back
                        </Link>
                    </div>

                    <form onSubmit={handleFormSubmit}>
                        <div className="w-full p-5 space-y-5">
                            <div className="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="form-control">
                                    <label htmlFor="name" className={`label`}>Name <span className={`text-xs`}> *</span></label>
                                    <input type="text" id={`name`} value={data.name} onChange={handleInput} className={`input`} placeholder={`Enter draw name`}/>
                                </div>
                                <div className="form-control">
                                    <label htmlFor="date" className={`label`}>Date <span className={`text-xs`}> *</span></label>
                                    <input type="date" id={`date`} value={data.date} onChange={handleInput} className={`input`}/>
                                </div>
                            </div>
                            <div className="w-full grid grid-cols-1 gap-4">
                                <div className="form-control">
                                    <div className="w-full flex justify-between items-center">
                                        <label htmlFor="file" className={`label`}>File Upload <span className={`text-xs`}> *</span></label>
                                        <a href="/draw_winners_example.xlsx" download className={`text-blue-500 cursor-pointer text-sm font-bold flex items-center gap-1 mb-2`}>Download Example <FaArrowRight className={`size-3`} /></a>
                                    </div>
                                    <ExcelUploader
                                        selectedFile={data.file}
                                        onFileChange={(file) => setData("file", file)}
                                    />
                                </div>
                            </div>

                            <div className="w-full">
                                <Button type={`submit`} buttonText={`Submit`} />
                            </div>
                        </div>
                    </form>
                </div>
            </Main>
        </>
)
}
