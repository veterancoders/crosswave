import { useInput } from "../hooks/useInput";

const ConvertModal = ({ closeModal, openModal }) => {
    const { value, handleInputChange } = useInput();

    const getValues = () => {
        console.log(value);
    };

    return (
        <section
            className={`fixed top-[15%] left-1/2 bg-white rounded-xl
        -translate-x-1/2 z-20 w-[90%] min-h-[400px] lg:min-h-[500px]
         md:w-3/5 shadow-md xl:w-2/5 max-w-2xl duration-100 ${
             openModal ? "scale-1" : "scale-0"
         }`}>
            <div className='relative p-4'>
                <button
                    className='absolute top-4 text-xl'
                    onClick={() => closeModal({ type: "CLOSE" })}>
                    X
                </button>

                <h1 className='mb-8 text-center flex flex-col'>
                    <span className='font-semibold text-xl'>Convert NGN</span>
                    <span className='flex-between inline text-sm opacity-60'>
                        <img
                            src='/assets/naira.png'
                            alt='naira'
                            className='inline w-3 mb-[2px]'
                        />
                        1,270.75 Available
                    </span>
                </h1>

                <div className='lg:w-4/5 lg:mx-auto'>
                    <div className='mb-4'>
                        <label
                            htmlFor='convert'
                            className='font-medium block mb-2'>
                            Convert
                        </label>

                        <div className='relative'>
                            <button className='absolute top-6 left-4'>
                                <img
                                    src='/assets/naijaflag.png'
                                    alt='nigerian flag'
                                    className='w-8 h-6 mr-1 inline'
                                />
                                NGN <span className='opacity-40'>|</span>
                            </button>

                            <input
                                type='text'
                                name='convert'
                                id='convert'
                                value={value.naira}
                                onChange={handleInputChange}
                                placeholder='0.00'
                                className='pb-4 pt-[1.3rem] pl-[100px] w-full text-lg md:w-4/5 border-gray-300 border-2 rounded-md outline-none'
                            />
                        </div>
                    </div>

                    <div className='flex'>
                        <p className='text-center text-sm'>
                            <span className='opacity-60'>Rate: </span>
                            <span className='flex-between inline font-semibold'>
                                <img
                                    src='/assets/naira.png'
                                    alt='naira'
                                    className='inline w-4 mb-[3px]'
                                />
                                1,183.4082 = $1.00
                            </span>
                        </p>
                    </div>

                    <div className='mt-4'>
                        <label htmlFor='to' className='font-medium block mb-2'>
                            To
                        </label>

                        <div className='relative'>
                            <button className='absolute top-6 left-4'>
                                <img
                                    src='/assets/usflag.png'
                                    alt='american flag'
                                    className='w-8 h-6 mr-1 inline'
                                />
                                USD <span className='opacity-40'>|</span>
                            </button>
                            <input
                                type='text'
                                name='to'
                                id='to'
                                value={value.usd}
                                onChange={handleInputChange}
                                placeholder='0.00'
                                className='pb-4 pt-[1.3rem] pl-[100px] w-full text-lg md:w-4/5 border-gray-300 border-2 rounded-md outline-none'
                            />
                        </div>
                    </div>

                    <div className='my-4 md:w-4/5'>
                        <button
                            className='bg-[#FFCD29] mr-3 rounded-xl shadow-md w-full p-4'
                            onClick={getValues}>
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default ConvertModal;
