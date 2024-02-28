import React from "react";
import { useTranslation } from "react-i18next";

const LanguageSwitcher = () => {
  const { i18n } = useTranslation();

  const handleLanguageChange = (e) => {
    const newLang = e.target.value;
    i18n.changeLanguage(newLang);
  };

  return (
    <select value={i18n.language} onChange={handleLanguageChange} data-testid="cypress-lang-switch" className="bg-transparent p-2 border border-cyan-300">
      <option value="en">ANG</option>
      <option value="fr">FRA</option>
      <option value="de">ALL</option>
    </select>
  );
};

export default LanguageSwitcher;