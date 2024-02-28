import i18n from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import { initReactI18next } from 'react-i18next';
import translationEN from "./locales/en.json";
import translationFR from "./locales/fr.json";
import translationDE from "./locales/de.json";

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    fallbackLng: 'en',
    resources: {
      en: {
        translation: translationEN
      },
      fr: {
        translation: translationFR
      },
      de: {
        translation: translationDE
      }
    },
    interpolation: {
      escapeValue: false,
    },
  });

export default i18n;