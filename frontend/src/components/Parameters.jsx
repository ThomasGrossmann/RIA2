import { useTranslation } from 'react-i18next';
import i18n from '../i18n';

const Parameters = () => {
  const { t } = useTranslation();

  return (
    <div className='flex flex-row justify-center gap-36'>
      <div className='flex flex-col w-2/4'>
        <label data-testid="cypress-max_labels">{t("max_labels")}</label>
        <input type='number' placeholder={t("max_labels_placeholder")} />
      </div>
      <div className='flex flex-col w-2/4'>
        <label data-testid="cypress-min_confidence">{t("min_confidence")}</label>
        <input type='number' placeholder={t("min_confidence_placeholder")} />
      </div>
    </div>
  );
}

export default Parameters;