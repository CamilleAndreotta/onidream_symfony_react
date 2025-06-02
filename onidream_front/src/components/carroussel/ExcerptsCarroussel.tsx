import { useEffect, useState } from "react";
import { ExcerptType } from "../../@types/Excerpt";
import { useExcerpts } from "../../context/ExcerptContext";

import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

const ExcerptsCarrousel = () => {

    const {excerpts} = useExcerpts();
    const [randomExcerpts, setRandomExcerpts] = useState<ExcerptType[]>([]);

    const getRandomExcerpts = (excerpts: ExcerptType[], count: number) => {
        const shuffled = [...excerpts].sort(() => 0.5 - Math.random());
        return shuffled.slice(0, count);
    }

    useEffect(() => {
        if(excerpts.length > 0) {
            const selected = getRandomExcerpts(excerpts, 5);
            setRandomExcerpts(selected);
        }
    }, [excerpts]);

    if (randomExcerpts.length === 0) return <div className="w-full flex items-center justify-center"><p>Aucun extrait</p></div>;

    return (
        <Swiper
        modules={[Navigation, Pagination, Autoplay]}
        spaceBetween={30}
        slidesPerView={1}
        navigation
        pagination={{ clickable: true }}
        autoplay={{ delay: 8000 }}
        loop
        className="max-w-xl mx-auto bg-white h-60 shadow-sm"
        >
        {randomExcerpts.map((excerpt, index) => (
            <SwiperSlide key={index}>
            <div className="h-full p-6 rounded shadow text-center min-h-[150px]">
                <p className="block bg-white p-4 "
                 dangerouslySetInnerHTML={{ __html: excerpt.text }} />
            </div>
            </SwiperSlide>
        ))}
        </Swiper>
    )
}


export default ExcerptsCarrousel;