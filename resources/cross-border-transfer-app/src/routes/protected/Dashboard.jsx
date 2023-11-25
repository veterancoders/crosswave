import { useContext } from "react";
import { BackdropContext } from "../../contexts/BackdropProvider";
import ConvertModal from "../../components/ConvertModal";
import { Link } from "react-router-dom";

const Dashboard = () => {
    const { modals, dispatch } = useContext(BackdropContext);

    return (
        <>
            <div className='md:w-4/5 md:mx-auto md:mt-8'>
                <section className='flex items-center'>
                    <button className='flex-between bg-white py-2 px-3 mr-3 rounded-xl shadow-lg'>
                        <img
                            src='/assets/naijaflag.png'
                            alt='flag'
                            className='w-8 h-8 mr-1'
                        />

                        <span>
                            <img src='/assets/unfold.svg' alt='' />
                        </span>
                    </button>

                    <p className='flex flex-col justify-center'>
                        <span className='text-xs'>Your Balance</span>
                        <span className='font-bold text-xl mt-1'>
                            <strong>NGN 1,200.75 </strong>
                            <img
                                src='/assets/eye-24.png'
                                alt='display balance'
                                className='inline-block w-4'
                            />
                        </span>
                    </p>
                </section>

                <section className='my-6 font-semibold text-sm'>
                    <p>
                        <button className='bg-white py-1 px-2 mr-3 rounded-xl shadow-md'>
                            <img
                                src='/assets/add.svg'
                                alt='add cash'
                                className='inline-block'
                            />
                            Add Cash
                        </button>
                        <button
                            className='bg-white py-2 px-3 mr-3 rounded-xl shadow-md'
                            onClick={() => dispatch({ type: "CONVERT" })}>
                            <img
                                src='/assets/convert.svg'
                                alt='convert cash'
                                className='inline-block w-4 rotate-90 mr-1'
                            />
                            Convert
                        </button>
                        <button className='bg-white py-1 px-4 mr-3 text-sm rounded-xl shadow-md'>
                            ...
                        </button>
                    </p>
                </section>

                <section className='my-6 font-bold text-lg flex-between md:w-4/5 lg:w-3/5'>
                    <button className='bg-white py-3 px-6 mr-3 rounded-xl shadow-md basis-1/2'>
                        Request
                    </button>

                    <button className='bg-[#FFCD29] mr-3 rounded-xl shadow-md basis-1/2'>
                        <Link
                            to='/transfer'
                            className='w-full inline-block py-3 px-6'>
                            Send
                        </Link>
                    </button>
                </section>

                <section className='my-24'>
                    <h2 className='mb-8 text-xl font-semibold'>
                        Transaction History
                    </h2>
                    <article className='flex-item-start my-4 pb-4 border-b-2 border-gray-200'>
                        <div
                            className='flex-center bg-[#323232] text-white text-sm
            rounded-[50%] w-8 h-8'>
                            <span>E</span>
                        </div>

                        <div className='flex-1 ml-3'>
                            <div className='flex items-start justify-between'>
                                <p className='flex flex-col'>
                                    <span className='font-medium'>
                                        You purchased Airtime
                                    </span>
                                    <span className='text-xs opacity-60'>
                                        Aug 21 at 9:50 PM
                                    </span>
                                </p>

                                <span className='flex-between text-sm'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline-block w-4'
                                    />
                                    28700.00
                                </span>
                            </div>

                            <p className='pt-2 text-sm'>
                                Sent{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    28700.00
                                </span>{" "}
                                worth of airtime to +234 7000001. You saved{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    10.00!
                                </span>
                            </p>
                        </div>
                    </article>

                    <article className='flex-item-start my-4 pb-4 border-b-2 border-gray-200'>
                        <div
                            className='flex-center bg-[#323232] text-white text-sm
            rounded-[50%] w-8 h-8'>
                            <span> E</span>
                        </div>

                        <div className='flex-1 ml-3'>
                            <div className='flex items-start justify-between'>
                                <p className='flex flex-col'>
                                    <span className='font-medium'>
                                        You purchased Airtime
                                    </span>
                                    <span className='text-xs opacity-60'>
                                        Aug 21 at 9:50 PM
                                    </span>
                                </p>

                                <span className='flex-between text-sm'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline-block w-4'
                                    />
                                    28700.00
                                </span>
                            </div>

                            <p className='pt-2 text-sm'>
                                Sent{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    28700.00
                                </span>{" "}
                                worth of airtime to +234 7000001. You saved{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    10.00!
                                </span>
                            </p>
                        </div>
                    </article>

                    <article className='flex-item-start my-4 pb-4'>
                        <div
                            className='flex-center bg-[#323232] text-white text-sm
            rounded-[50%] w-8 h-8'>
                            <span> E</span>
                        </div>

                        <div className='flex-1 ml-3'>
                            <div className='flex items-start justify-between'>
                                <p className='flex flex-col'>
                                    <span className='font-medium'>
                                        You purchased Airtime
                                    </span>
                                    <span className='text-xs opacity-60'>
                                        Aug 21 at 9:50 PM
                                    </span>
                                </p>

                                <span className='flex-between text-sm'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline-block w-4'
                                    />
                                    28700.00
                                </span>
                            </div>

                            <p className='pt-2 text-sm'>
                                Sent{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    28700.00
                                </span>{" "}
                                worth of airtime to +234 7000001. You saved{" "}
                                <span className='flex-between inline'>
                                    <img
                                        src='/assets/naira.png'
                                        alt='naira'
                                        className='inline w-3 mb-[2px]'
                                    />
                                    10.00!
                                </span>
                            </p>
                        </div>
                    </article>
                </section>
            </div>

            <ConvertModal
                closeModal={dispatch}
                openModal={modals.convertModal}
            />
            {/* {modals.convertModal && <ConvertModal closeModal={dispatch} />} */}
        </>
    );
};

export default Dashboard;
