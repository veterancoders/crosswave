const TransferModal = () => {
    return (
        <div className='md:w-4/5 md:mx-auto md:mt-8'>
            {/* <section
            className='fixed top-[15%] left-1/2 bg-white rounded-xl
        -translate-x-1/2 z-20 w-[90%] h-[400px] lg:h-[500px] md:w-3/5 lg:top-[20%] shadow-md xl:w-2/5 max-w-2xl'> */}
            <div className='relative p-4'>
                {/* <button
                    className='absolute top-4 text-xl'
                    onClick={() => closeModal("close")}>
                    X
                </button> */}

                <h1 className='mb-8 text-xl text-center font-semibold'>
                    Send Money
                </h1>

                <div>
                    <div className='flex-between lg:max-w-[60%] xl:max-w-[50%]'>
                        <span>
                            <img
                                src='/assets/world.png'
                                alt='worldwide icon'
                                className='inline-block'
                            />
                        </span>

                        <div className='flex-1 ml-3'>
                            <div className='flex items-start justify-between'>
                                <p className='flex flex-col'>
                                    <span className='font-medium'>
                                        Send Internationally
                                    </span>
                                    <span className='text-xs opacity-60'>
                                        To 3 countries
                                    </span>
                                </p>

                                <span className='font-bold text-lg'>{">"}</span>
                            </div>
                        </div>
                    </div>

                    <div className='mt-8'>
                        <h2>
                            <span className='text-sm opacity-60'>
                                AVAILABLE OPTIONS IN NIGERIA
                            </span>
                            <img
                                src='/assets/naijaflag.png'
                                alt='flag'
                                className='inline w-4 h-4 ml-1'
                            />
                        </h2>

                        <div className='lg:max-w-[60%] xl:max-w-[50%]'>
                            <div className='flex items-center mt-4 xl:justify-start'>
                                <div
                                    className='flex-center bg-[#323232] text-white text-sm
            rounded-[50%] w-10 h-8 md:w-8 xl:h-6'>
                                    <span>CW</span>
                                </div>

                                <p className='flex flex-col ml-4'>
                                    <span className='font-medium'>
                                        CrossWave Tag
                                    </span>
                                    <span className='text-xs opacity-60 pr-4'>
                                        Send to a CrossWave tag or invite phone
                                        contact
                                    </span>
                                </p>

                                <div className='text-sm ml-auto'>
                                    <strong className='text-green-600 mr-1 md:mr-2'>
                                        Free
                                    </strong>
                                    <strong className='font-bold text-lg'>
                                        {">"}
                                    </strong>
                                </div>
                            </div>

                            <div className='flex items-center mt-8 xl:justify-start'>
                                <div
                                    className='flex-center bg-[#323232] text-white text-sm
            rounded-[50%] w-8 h-8 md:w-8 xl:h-6'>
                                    <span>CW</span>
                                </div>

                                <p className='flex flex-col ml-4'>
                                    <span className='font-medium'>
                                        NGN Bank Accounts
                                    </span>
                                    <span className='text-xs opacity-60'>
                                        Send to a bank account
                                    </span>
                                </p>

                                <div className='text-sm ml-auto'>
                                    <strong className='font-bold text-lg'>
                                        {">"}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default TransferModal;
