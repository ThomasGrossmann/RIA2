import { useTranslation } from 'react-i18next';
import i18n from '../i18n';

const Parameters = () => {
  const { t } = useTranslation();

  return (
    <div className='flex flex-col justify-center gap-3 md:flex-row md:gap-20'>
      <div className='flex flex-col w-full md:w-2/4'>
        <label data-testid="cypress-max_labels">{t("max_labels")}</label>
        <input type='number' placeholder={t("max_labels_placeholder")} min="0" className='p-2' />
      </div>
      <div className='flex flex-col w-full md:w-2/4'>
        <label data-testid="cypress-min_confidence">{t("min_confidence")}</label>
        <input type='number' placeholder={t("min_confidence_placeholder")} min="0" max="100" className='p-2' />
      </div>
    </div>
  );
}

export default Parameters;